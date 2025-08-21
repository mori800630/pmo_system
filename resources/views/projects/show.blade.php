@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- プロジェクト情報 -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $project->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">顧客: {{ $project->customer_name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        編集
                    </a>
                    <form action="https://pmosystem-production.up.railway.app/projects/{{ $project->id }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('本当に削除しますか？')">
                            削除
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">進捗ヘルス</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->health_color_class }}">
                            {{ $project->health_name }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">PM名</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $project->pm_name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">優先度</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->priority_color_class }}">
                            {{ $project->priority_name }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">フェーズ</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $project->phase_name }}</dd>
                </div>
                @if($project->budget)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">予算</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">¥{{ number_format($project->budget) }}</dd>
                </div>
                @endif
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">計画期間</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($project->baseline_start_date && $project->baseline_end_date)
                            {{ $project->baseline_start_date->format('Y年m月d日') }} ～ {{ $project->baseline_end_date->format('Y年m月d日') }}
                        @else
                            未設定
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">実績期間</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($project->actual_start_date && $project->actual_end_date)
                            {{ $project->actual_start_date->format('Y年m月d日') }} ～ {{ $project->actual_end_date->format('Y年m月d日') }}
                        @elseif($project->actual_start_date)
                            {{ $project->actual_start_date->format('Y年m月d日') }} ～ 進行中
                        @else
                            未設定
                        @endif
                    </dd>
                </div>
                @if($project->deliverables_summary)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">成果物概要</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ $project->deliverables_summary }}</dd>
                </div>
                @endif
                @if($project->main_links && count(array_filter($project->main_links)))
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">主要リンク</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            @foreach($project->main_links as $index => $link)
                                @if($link)
                                    <div>
                                        @switch($index)
                                            @case(0)
                                                <span class="text-gray-500">Backlog/Issue:</span>
                                                @break
                                            @case(1)
                                                <span class="text-gray-500">Gitリポジトリ:</span>
                                                @break
                                            @case(2)
                                                <span class="text-gray-500">社内Wiki:</span>
                                                @break
                                        @endswitch
                                        <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:text-blue-800 ml-1">{{ $link }}</a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- チェックリスト -->
    <div class="space-y-8">
        <!-- 計画フェーズ -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-yellow-50">
                <h3 class="text-lg leading-6 font-medium text-yellow-900">計画フェーズ</h3>
                <p class="mt-1 max-w-2xl text-sm text-yellow-700">プロジェクトの立ち上げと計画策定（プロジェクト内容の理解、体制確認、スケジュール策定、リスク分析など）</p>
            </div>
            <div class="border-t border-yellow-200">
                @php
                    $planningCompleted = $project->planningChecklists->where('is_completed', true)->count();
                    $planningTotal = $project->planningChecklists->count();
                    $planningPercent = $planningTotal ? intval($planningCompleted / $planningTotal * 100) : 0;
                @endphp
                <div class="px-4 py-3 bg-white">
                    <div class="flex items-center justify-between mb-2 text-sm text-gray-700">
                        <span>進捗: <span class="font-medium">{{ $planningCompleted }} / {{ $planningTotal }}</span></span>
                        <span class="tabular-nums">{{ $planningPercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-yellow-400 rounded" style="width: {{ $planningPercent }}%"></div>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($project->planningChecklists as $checklist)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded checklist-toggle"
                                       data-id="{{ $checklist->id }}"
                                       {{ $checklist->is_completed ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-medium text-gray-900 {{ $checklist->is_completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $checklist->title }}
                                </span>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $checklist->status_color_class }}">
                                    {{ $checklist->status_name }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 text-sm" onclick="editChecklist({{ $checklist->id }}, '{{ $checklist->title }}', '{{ $checklist->description }}')">
                                    編集
                                </button>
                                @if(auth()->user()->isUser() || auth()->user()->isAdmin() || auth()->user()->isPmoManager())
                                    @if($checklist->status === 'draft' || $checklist->status === 'rejected')
                                    <form action="{{ route('checklists.submit', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">提出</button>
                                    </form>
                                    @endif
                                @endif
                                @if(auth()->user()->isPmoManager() || auth()->user()->isAdmin())
                                    @if($checklist->status === 'submitted')
                                    <form action="{{ route('checklists.startReview', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-700 hover:text-yellow-900 text-sm">レビュー開始</button>
                                    </form>
                                    @endif
                                    @if($checklist->status === 'under_review' || $checklist->status === 'submitted')
                                    <form action="{{ route('checklists.approve', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="">
                                        <button type="submit" class="text-green-700 hover:text-green-900 text-sm">承認</button>
                                    </form>
                                    <form action="{{ route('checklists.reject', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="修正してください">
                                        <button type="submit" class="text-red-700 hover:text-red-900 text-sm">差戻し</button>
                                    </form>
                                    @endif
                                @endif
                                <form action="{{ route('checklists.destroy', $checklist) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('削除しますか？')">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($checklist->description)
                        <p class="mt-1 text-sm text-gray-500 ml-7 whitespace-pre-line">{{ $checklist->description }}</p>
                        @endif
                    </li>
                    @empty
                    <li class="px-4 py-4 text-sm text-gray-500">チェックリスト項目がありません</li>
                    @endforelse
                </ul>
                <div class="px-4 py-3 bg-gray-50">
                    <button onclick="showAddForm('planning')" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        + 項目を追加
                    </button>
                </div>
            </div>
        </div>

        <!-- 実行フェーズ -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-blue-50">
                <h3 class="text-lg leading-6 font-medium text-blue-900">実行フェーズ</h3>
                <p class="mt-1 max-w-2xl text-sm text-blue-700">プロジェクトの実行・監視（キックオフ、課題管理、進捗管理、品質管理など）</p>
            </div>
            <div class="border-t border-blue-200">
                @php
                    $executionCompleted = $project->executionChecklists->where('is_completed', true)->count();
                    $executionTotal = $project->executionChecklists->count();
                    $executionPercent = $executionTotal ? intval($executionCompleted / $executionTotal * 100) : 0;
                @endphp
                <div class="px-4 py-3 bg-white">
                    <div class="flex items-center justify-between mb-2 text-sm text-gray-700">
                        <span>進捗: <span class="font-medium">{{ $executionCompleted }} / {{ $executionTotal }}</span></span>
                        <span class="tabular-nums">{{ $executionPercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-blue-400 rounded" style="width: {{ $executionPercent }}%"></div>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($project->executionChecklists as $checklist)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded checklist-toggle"
                                       data-id="{{ $checklist->id }}"
                                       {{ $checklist->is_completed ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-medium text-gray-900 {{ $checklist->is_completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $checklist->title }}
                                </span>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $checklist->status_color_class }}">
                                    {{ $checklist->status_name }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 text-sm" onclick="editChecklist({{ $checklist->id }}, '{{ $checklist->title }}', '{{ $checklist->description }}')">
                                    編集
                                </button>
                                @if(auth()->user()->isUser() || auth()->user()->isAdmin() || auth()->user()->isPmoManager())
                                    @if($checklist->status === 'draft' || $checklist->status === 'rejected')
                                    <form action="{{ route('checklists.submit', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">提出</button>
                                    </form>
                                    @endif
                                @endif
                                @if(auth()->user()->isPmoManager() || auth()->user()->isAdmin())
                                    @if($checklist->status === 'submitted')
                                    <form action="{{ route('checklists.startReview', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-700 hover:text-yellow-900 text-sm">レビュー開始</button>
                                    </form>
                                    @endif
                                    @if($checklist->status === 'under_review' || $checklist->status === 'submitted')
                                    <form action="{{ route('checklists.approve', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="">
                                        <button type="submit" class="text-green-700 hover:text-green-900 text-sm">承認</button>
                                    </form>
                                    <form action="{{ route('checklists.reject', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="修正してください">
                                        <button type="submit" class="text-red-700 hover:text-red-900 text-sm">差戻し</button>
                                    </form>
                                    @endif
                                @endif
                                <form action="{{ route('checklists.destroy', $checklist) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('削除しますか？')">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($checklist->description)
                        <p class="mt-1 text-sm text-gray-500 ml-7 whitespace-pre-line">{{ $checklist->description }}</p>
                        @endif
                    </li>
                    @empty
                    <li class="px-4 py-4 text-sm text-gray-500">チェックリスト項目がありません</li>
                    @endforelse
                </ul>
                <div class="px-4 py-3 bg-gray-50">
                    <button onclick="showAddForm('execution')" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        + 項目を追加
                    </button>
                </div>
            </div>
        </div>

        <!-- 終結フェーズ -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-green-50">
                <h3 class="text-lg leading-6 font-medium text-green-900">終結フェーズ</h3>
                <p class="mt-1 max-w-2xl text-sm text-green-700">プロジェクトの終結（成果物確認、結果評価、ポストモーテム実施など）</p>
            </div>
            <div class="border-t border-green-200">
                @php
                    $completionCompleted = $project->completionChecklists->where('is_completed', true)->count();
                    $completionTotal = $project->completionChecklists->count();
                    $completionPercent = $completionTotal ? intval($completionCompleted / $completionTotal * 100) : 0;
                @endphp
                <div class="px-4 py-3 bg-white">
                    <div class="flex items-center justify-between mb-2 text-sm text-gray-700">
                        <span>進捗: <span class="font-medium">{{ $completionCompleted }} / {{ $completionTotal }}</span></span>
                        <span class="tabular-nums">{{ $completionPercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-green-400 rounded" style="width: {{ $completionPercent }}%"></div>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($project->completionChecklists as $checklist)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded checklist-toggle"
                                       data-id="{{ $checklist->id }}"
                                       {{ $checklist->is_completed ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-medium text-gray-900 {{ $checklist->is_completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $checklist->title }}
                                </span>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $checklist->status_color_class }}">
                                    {{ $checklist->status_name }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 text-sm" onclick="editChecklist({{ $checklist->id }}, '{{ $checklist->title }}', '{{ $checklist->description }}')">
                                    編集
                                </button>
                                @if(auth()->user()->isUser() || auth()->user()->isAdmin() || auth()->user()->isPmoManager())
                                    @if($checklist->status === 'draft' || $checklist->status === 'rejected')
                                    <form action="{{ route('checklists.submit', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">提出</button>
                                    </form>
                                    @endif
                                @endif
                                @if(auth()->user()->isPmoManager() || auth()->user()->isAdmin())
                                    @if($checklist->status === 'submitted')
                                    <form action="{{ route('checklists.startReview', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-700 hover:text-yellow-900 text-sm">レビュー開始</button>
                                    </form>
                                    @endif
                                    @if($checklist->status === 'under_review' || $checklist->status === 'submitted')
                                    <form action="{{ route('checklists.approve', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="">
                                        <button type="submit" class="text-green-700 hover:text-green-900 text-sm">承認</button>
                                    </form>
                                    <form action="{{ route('checklists.reject', $checklist) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="review_comment" value="修正してください">
                                        <button type="submit" class="text-red-700 hover:text-red-900 text-sm">差戻し</button>
                                    </form>
                                    @endif
                                @endif
                                <form action="{{ route('checklists.destroy', $checklist) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('削除しますか？')">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($checklist->description)
                        <p class="mt-1 text-sm text-gray-500 ml-7 whitespace-pre-line">{{ $checklist->description }}</p>
                        @endif
                    </li>
                    @empty
                    <li class="px-4 py-4 text-sm text-gray-500">チェックリスト項目がありません</li>
                    @endforelse
                </ul>
                <div class="px-4 py-3 bg-gray-50">
                    <button onclick="showAddForm('completion')" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        + 項目を追加
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- チェックリスト追加モーダル -->
<div id="addChecklistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-3/4 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">チェックリスト項目を追加</h3>
            <form id="addChecklistForm" action="https://pmosystem-production.up.railway.app/projects/{{ $project->id }}/checklists" method="POST">
                @csrf
                <input type="hidden" name="phase" id="phase">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">タイトル *</label>
                    <input type="text" name="title" id="title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
                    <textarea name="description" id="description" rows="8"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm resize-y"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideAddForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        キャンセル
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        追加
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- チェックリスト編集モーダル -->
<div id="editChecklistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-3/4 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">チェックリスト項目を編集</h3>
            <form id="editChecklistForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_title" class="block text-sm font-medium text-gray-700">タイトル *</label>
                    <input type="text" name="title" id="edit_title" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">説明</label>
                    <textarea name="description" id="edit_description" rows="8"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm resize-y"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideEditForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        キャンセル
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// チェックリストの完了状態を切り替え
document.querySelectorAll('.checklist-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checklistId = this.dataset.id;
        const isChecked = this.checked;
        
        fetch(`/checklists/${checklistId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const titleSpan = this.closest('li').querySelector('span');
                if (data.is_completed) {
                    titleSpan.classList.add('line-through', 'text-gray-500');
                } else {
                    titleSpan.classList.remove('line-through', 'text-gray-500');
                }
            }
        });
    });
});

// チェックリスト追加フォームを表示
function showAddForm(phase) {
    document.getElementById('phase').value = phase;
    document.getElementById('addChecklistModal').classList.remove('hidden');
}

// チェックリスト追加フォームを非表示
function hideAddForm() {
    document.getElementById('addChecklistModal').classList.add('hidden');
    document.getElementById('addChecklistForm').reset();
}

// チェックリスト編集フォームを表示
function editChecklist(id, title, description) {
    document.getElementById('editChecklistForm').action = `/checklists/${id}`;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('editChecklistModal').classList.remove('hidden');
}

// チェックリスト編集フォームを非表示
function hideEditForm() {
    document.getElementById('editChecklistModal').classList.add('hidden');
}
</script>
@endsection
