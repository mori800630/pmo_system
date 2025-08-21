<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Checklist;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * プロジェクト一覧を表示
     */
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * プロジェクト作成フォームを表示
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * プロジェクトを保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pm_name' => 'required|string|max:255',
            'health' => 'required|in:Green,Amber,Red',
            'customer_name' => 'required|string|max:255',
            'priority' => 'required|in:High,Medium,Low',
            'phase' => 'required|in:planning,requirements,design,implementation,testing,release,operation',
            'budget' => 'nullable|numeric|min:0',
            'baseline_start_date' => 'nullable|date',
            'baseline_end_date' => 'nullable|date|after_or_equal:baseline_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'deliverables_summary' => 'nullable|string',
            'main_links' => 'nullable|array',
            'main_links.*' => 'nullable|url',
        ]);

        $data = $request->all();
        // main_linksをJSON形式で保存
        if (isset($data['main_links'])) {
            $data['main_links'] = array_filter($data['main_links']); // 空の値を削除
        }

        $data['created_by'] = auth()->id();
        $project = Project::create($data);

        // デフォルトのチェックリスト項目を作成
        $this->createDefaultChecklists($project);

        return redirect()->route('projects.show', $project)
            ->with('success', 'プロジェクトが正常に作成されました。');
    }

    /**
     * プロジェクト詳細を表示
     */
    public function show(Project $project)
    {
        $project->load(['checklists' => function($query) {
            $query->orderBy('phase')->orderBy('order');
        }]);

        return view('projects.show', compact('project'));
    }

    /**
     * プロジェクト編集フォームを表示
     */
    public function edit(Project $project)
    {
        if (!$project->canEditBy(auth()->user())) {
            abort(403, 'このプロジェクトを編集する権限がありません。');
        }
        
        return view('projects.edit', compact('project'));
    }

    /**
     * プロジェクトを更新
     */
    public function update(Request $request, Project $project)
    {
        if (!$project->canEditBy(auth()->user())) {
            abort(403, 'このプロジェクトを編集する権限がありません。');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'pm_name' => 'required|string|max:255',
            'health' => 'required|in:Green,Amber,Red',
            'customer_name' => 'required|string|max:255',
            'priority' => 'required|in:High,Medium,Low',
            'phase' => 'required|in:planning,requirements,design,implementation,testing,release,operation',
            'budget' => 'nullable|numeric|min:0',
            'baseline_start_date' => 'nullable|date',
            'baseline_end_date' => 'nullable|date|after_or_equal:baseline_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'deliverables_summary' => 'nullable|string',
            'main_links' => 'nullable|array',
            'main_links.*' => 'nullable|url',
        ]);

        $data = $request->all();
        // main_linksをJSON形式で保存
        if (isset($data['main_links'])) {
            $data['main_links'] = array_filter($data['main_links']); // 空の値を削除
        }

        $project->update($data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'プロジェクトが正常に更新されました。');
    }

    /**
     * プロジェクトを削除
     */
    public function destroy(Project $project)
    {
        if (!$project->canEditBy(auth()->user())) {
            abort(403, 'このプロジェクトを削除する権限がありません。');
        }
        
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'プロジェクトが正常に削除されました。');
    }

    /**
     * フェーズを提出
     */
    public function submitPhase(Request $request, Project $project, $phase)
    {
        if (!$project->canEditBy(auth()->user())) {
            abort(403, 'このプロジェクトを編集する権限がありません。');
        }

        $statusField = $phase . '_status';
        $submittedByField = $phase . '_submitted_by';
        $submittedAtField = $phase . '_submitted_at';

        $project->update([
            $statusField => 'submitted',
            $submittedByField => auth()->id(),
            $submittedAtField => now(),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', ucfirst($phase) . 'フェーズを提出しました。');
    }

    /**
     * フェーズのレビュー開始
     */
    public function startPhaseReview(Request $request, Project $project, $phase)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $statusField = $phase . '_status';
        $project->update([$statusField => 'under_review']);

        return redirect()->route('projects.show', $project)
            ->with('success', ucfirst($phase) . 'フェーズのレビューを開始しました。');
    }

    /**
     * フェーズを承認
     */
    public function approvePhase(Request $request, Project $project, $phase)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $statusField = $phase . '_status';
        $reviewedByField = $phase . '_reviewed_by';
        $reviewedAtField = $phase . '_reviewed_at';
        $reviewCommentField = $phase . '_review_comment';

        $project->update([
            $statusField => 'approved',
            $reviewedByField => auth()->id(),
            $reviewedAtField => now(),
            $reviewCommentField => $request->input('review_comment'),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', ucfirst($phase) . 'フェーズを承認しました。');
    }

    /**
     * フェーズを差戻し
     */
    public function rejectPhase(Request $request, Project $project, $phase)
    {
        if (!auth()->user()->isPmoManager() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'review_comment' => 'required|string',
        ]);

        $statusField = $phase . '_status';
        $reviewedByField = $phase . '_reviewed_by';
        $reviewedAtField = $phase . '_reviewed_at';
        $reviewCommentField = $phase . '_review_comment';

        $project->update([
            $statusField => 'rejected',
            $reviewedByField => auth()->id(),
            $reviewedAtField => now(),
            $reviewCommentField => $request->input('review_comment'),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', ucfirst($phase) . 'フェーズを差戻しました。');
    }

    /**
     * デフォルトのチェックリスト項目を作成
     */
    private function createDefaultChecklists(Project $project)
    {
        $defaultChecklists = [
            // 計画フェーズ（質問文をタイトルに、カテゴリ名を説明に）
            ['phase' => 'planning', 'title' => 'なぜこの案件を実施するのか（背景や経緯）', 'description' => 'プロジェクト内容の理解・共有', 'order' => 1],
            ['phase' => 'planning', 'title' => '何を達成したいのか（目的・ゴール・成功の定義）', 'description' => 'プロジェクトのゴールの理解', 'order' => 2],
            ['phase' => 'planning', 'title' => '社内での対応体制（PM／BSE／PGなどの役割と担当）', 'description' => '社内体制の確認', 'order' => 3],
            ['phase' => 'planning', 'title' => '外部委託利用の有無、およびその選定ができているか？参加する場合、情報共有手段（ドキュメント／会議）は整っているか？', 'description' => '外部委託利用の有無、およびその選定', 'order' => 4],
            ['phase' => 'planning', 'title' => '開発メンバーの体制が確定しているか？担当範囲・責任が曖昧な人がいないか？リソースの過不足（偏り）がないか？', 'description' => '開発メンバーの体制確定', 'order' => 5],
            ['phase' => 'planning', 'title' => '関係者間でのQAの実施方法は明確か？', 'description' => '関係者間でのQAの実施方法', 'order' => 6],
            ['phase' => 'planning', 'title' => '関係者間での資料の受け渡し方法は明確か？', 'description' => '関係者間での資料の受け渡し方法', 'order' => 7],
            ['phase' => 'planning', 'title' => '進捗報告のルール、フォーマット決めはできているか？', 'description' => '進捗報告のルール、フォーマット決め', 'order' => 8],
            ['phase' => 'planning', 'title' => '相談相手として相応しい人が確保できているか？', 'description' => '相談相手として相応しい人の確保', 'order' => 9],
            ['phase' => 'planning', 'title' => '誰が、仕様を決定し、最終判断をするのか？', 'description' => '仕様決定・最終判断者の明確化', 'order' => 10],
            ['phase' => 'planning', 'title' => 'プロジェクトに関わる人で兼務の人はいるか？どのくらいの稼働率を想定いるのか明確か？', 'description' => '各メンバーの稼働率', 'order' => 11],
            ['phase' => 'planning', 'title' => '想定スケジュール（開始〜納品まで）は明確か？', 'description' => '想定スケジュールの明確化', 'order' => 12],
            ['phase' => 'planning', 'title' => '継続案件の場合、前年度からの変更点は明確か？', 'description' => '継続案件の場合の影響確認', 'order' => 13],
            ['phase' => 'planning', 'title' => '成果物や機能（見積もり根拠が算出可能となるレベルで）が明確になっているか', 'description' => '見積もり・成果物・機能の妥当性検証', 'order' => 14],
            ['phase' => 'planning', 'title' => '過去のプロジェクトで参考にできる資料はあるか？また、経験者等に話を聞いたりすることが可能か？', 'description' => '参考にできる過去プロジェクトの確認', 'order' => 15],
            ['phase' => 'planning', 'title' => '品質に関する合意（品質目標・受け入れ条件）が定義され、関係者間で共有・記録されているか？', 'description' => '顧客との合意', 'order' => 16],
            ['phase' => 'planning', 'title' => '現時点で、『難所だな』と感じる作業はあるか？', 'description' => '現時点で見えているリスクの確認', 'order' => 17],
            ['phase' => 'planning', 'title' => '『こうなったら困るな』という漠然とした不安はないか？', 'description' => 'まだ見えていないリスクの確認', 'order' => 18],
            ['phase' => 'planning', 'title' => '仕様変更の起票～影響分析～合意の手順が明文化されているか？責任者・判断基準・記録の運用方針は決まっているか？', 'description' => '仕様変更への対応プロセス', 'order' => 19],
            ['phase' => 'planning', 'title' => '設計・開発事業者のタスクだけではなく、発注側のタスクも含めたWBSを作成できているか', 'description' => 'WBS', 'order' => 20],
            
            // 実行フェーズ
            ['phase' => 'execution', 'title' => 'キックオフミーティング', 'description' => 'キックオフミーティングで、プロジェクトの目的、目標、役割を明確に語ったか？※何のための仕事かを理解してもらう', 'order' => 1],
            ['phase' => 'execution', 'title' => '課題管理表の作成・運用', 'description' => '課題を一覧化していつでも見られる状況になっているか？誰が責任をもって対応するかわかるようにしているか？いつまでに対応が必要か期限がわかっているか？', 'order' => 2],
            ['phase' => 'execution', 'title' => '課題の優先順位付け', 'description' => '課題の対応について優先順位をつけているか？', 'order' => 3],
            ['phase' => 'execution', 'title' => 'リスク管理表の作成・運用', 'description' => 'リスクを一覧化していつでも見られる状況になっているか？誰が責任をもって対応するかわかるようにしているか？いつまでに対応が必要か期限がわかっているか？', 'order' => 4],
            ['phase' => 'execution', 'title' => 'リスクの優先順位付け', 'description' => 'リスクの対応について優先順位をつけているか？', 'order' => 5],
            ['phase' => 'execution', 'title' => '工程の完了条件の明確化', 'description' => '該当を工程を開始する前に、工程を完了（終了するための）条件が明確になっているか？その工程で発生した課題が解決しているか（または、いつまでに解決できることが明らかになっているか）', 'order' => 6],
            ['phase' => 'execution', 'title' => 'WBSの進捗記録', 'description' => 'WBSに進捗内容が正しく記録され、現状を把握・共有できる状態になっているか', 'order' => 7],
            ['phase' => 'execution', 'title' => '成果物チェック', 'description' => '計画時に定義した成果物（内容・形式）と一致しているか？受け入れ条件（完了定義）を満たしているか？', 'order' => 8],
            ['phase' => 'execution', 'title' => '作業タスク漏れチェック', 'description' => '計画時に定義した作業・機能が、すべてタスク作成されているか', 'order' => 9],
            ['phase' => 'execution', 'title' => 'タスクの作業順序確認', 'description' => 'タスクを適切な順序でアサインしているか', 'order' => 10],
            ['phase' => 'execution', 'title' => 'タスクの期限確認', 'description' => '期限は明確か。２週間以上など、長すぎるタスクはないか', 'order' => 11],
            ['phase' => 'execution', 'title' => 'タスク担当者の妥当性確認', 'description' => '担当者のアサインは適切か', 'order' => 12],
            ['phase' => 'execution', 'title' => '議事録の作成', 'description' => '会議後は必ず議事録を作成し、議題・合意内容の証跡を残しているか', 'order' => 13],
            ['phase' => 'execution', 'title' => '各工程でのレビュー・検証', 'description' => '各工程（要件・設計・開発・テスト）で、レビュー／検証されているか。レビュー対象（成果物）と観点が明確か', 'order' => 14],
            ['phase' => 'execution', 'title' => '仕様変更管理', 'description' => '要件・仕様の変更が発生した場合、内容を記録し、影響分析を行っているか', 'order' => 15],
            ['phase' => 'execution', 'title' => 'マニュアル作成および引き渡し', 'description' => 'マニュアルの更新は完了しているか。顧客側の引継ぎが完了しているか。社内の他メンバが理解可能な状態になっているか', 'order' => 16],
            
            // 終結フェーズ
            ['phase' => 'completion', 'title' => '成果物の確認', 'description' => 'プロジェクトの成果、成果物を今後、参照するかも知れない人のために残せているか', 'order' => 1],
            ['phase' => 'completion', 'title' => 'プロジェクトの結果の評価', 'description' => 'プロジェクトの結果を「品質・コスト・納期」で評価できる状態か', 'order' => 2],
            ['phase' => 'completion', 'title' => 'ポストモーテムの実施', 'description' => '振り返りを実施し、次案件や類似案件の教訓にできる状態か', 'order' => 3],
        ];

        foreach ($defaultChecklists as $checklist) {
            $project->checklists()->create($checklist);
        }
    }
}
