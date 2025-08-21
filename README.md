# PMOシステム (PMO System)

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11.x-red.svg" alt="Laravel Version">
<img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
<img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg" alt="Tailwind CSS">
<img src="https://img.shields.io/badge/PostgreSQL-15+-336791.svg" alt="PostgreSQL">
</p>

## 概要

PMOシステムは、プロジェクト管理を効率化するためのWebアプリケーションです。プロジェクトの進捗管理、チェックリスト機能、リスク管理を統合的にサポートします。

### 主な機能

- **ユーザー認証・管理**
  - ユーザー認証（ユーザーID/メールアドレス + パスワード）
  - ロールベースアクセス制御（管理者/PMOマネージャー/ユーザー）
  - ユーザー管理機能（管理者のみ）

- **プロジェクト管理**
  - プロジェクトの登録・編集・削除
  - 進捗ヘルス（Green/Amber/Red）による状況把握
  - 優先度管理（High/Medium/Low）
  - フェーズ管理（企画・要件・設計・実装・テスト・リリース・運用）

- **チェックリスト機能**
  - 計画・実行・終結の3フェーズ別チェックリスト
  - 進捗状況の可視化
  - カスタムチェックリスト項目の追加・編集

- **プロジェクト情報管理**
  - 顧客情報管理
  - 予算管理
  - 計画期間・実績期間の管理
  - 成果物概要の記録
  - 主要リンク（Backlog/Issue、Gitリポジトリ、社内Wiki）の管理

### 権限体系

- **Admin（システム管理者）**: ユーザー管理（作成/編集/削除/権限変更）、チェックリストテンプレート管理、システム設定
- **PMO Manager（PMOマネージャ）**: 全プロジェクト閲覧/管理、チェックリストの承認/差戻し/質疑、レポート閲覧
- **User（PM）**: 自身の担当プロジェクト管理、チェックリスト入力・編集、ドラフト保存・提出、PMOと質疑応答

### チェックリスト ワークフロー

Draft（下書き） → Submitted（提出） → Under Review（レビュー中） → Approved（承認）/ Rejected（差戻し）

- PM（User）は Draft/Rejected を編集し、Submitted に提出可能
- PMO Manager/Admin は Submitted を Under Review にし、Approve/Reject 可能

## 技術スタック

### バックエンド
- **Laravel 11.x** - PHP Webフレームワーク
- **PHP 8.2+** - プログラミング言語
- **PostgreSQL** - データベース
- **Eloquent ORM** - データベース操作
- **Laravel Breeze** - 認証スカフォールディング

### フロントエンド
- **Tailwind CSS** - CSSフレームワーク（CDN版）
- **Blade** - テンプレートエンジン
- **Alpine.js** - 軽量JavaScriptフレームワーク

### インフラ・デプロイ
- **Railway** - PaaS（Platform as a Service）
- **GitHub** - バージョン管理
- **Docker** - コンテナ化（Railwayで自動管理）

## セットアップ

### 前提条件
- PHP 8.2以上
- Composer
- Node.js（開発時のみ）
- PostgreSQL

### ローカル開発環境

1. **リポジトリのクローン**
```bash
git clone https://github.com/mori800630/pmo_system.git
cd pmo_system
```

2. **依存関係のインストール**
```bash
composer install
npm install
```

3. **環境設定**
```bash
cp .env.example .env
php artisan key:generate
```

4. **データベース設定**
```bash
# .envファイルでデータベース接続情報を設定
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
```

5. **アセットのビルド**
```bash
npm run build
```

6. **開発サーバーの起動**
```bash
php artisan serve
```

### 初期ログイン情報
- **ユーザー名**: `Admin`
- **パスワード**: `Password123!`
- **権限**: 管理者

### 本番環境（Railway）

1. **Railwayプロジェクトの作成**
2. **GitHubリポジトリとの連携**
3. **環境変数の設定**
4. **自動デプロイの確認**

## データベース構造

### ユーザーテーブル（users）
- `id` - 主キー
- `name` - 名前
- `username` - ユーザーID（ログイン用）
- `email` - メールアドレス（オプション）
- `password` - パスワード（ハッシュ化）
- `role` - 権限（admin/pmo_manager/user）
- `created_at` - 作成日時
- `updated_at` - 更新日時

### プロジェクトテーブル（projects）
- `id` - 主キー
- `name` - プロジェクト名
- `pm_name` - PM名
- `health` - 進捗ヘルス（Green/Amber/Red）
- `customer_name` - 顧客名
- `priority` - 優先度（High/Medium/Low）
- `phase` - フェーズ
- `budget` - 予算
- `baseline_start_date` - 計画開始日
- `baseline_end_date` - 計画終了日
- `actual_start_date` - 実績開始日
- `actual_end_date` - 実績終了日
- `deliverables_summary` - 成果物概要
- `main_links` - 主要リンク（JSON）

### チェックリストテーブル（checklists）
- `id` - 主キー
- `project_id` - プロジェクトID（外部キー）
- `phase` - フェーズ
- `title` - タイトル
- `description` - 説明
- `is_completed` - 完了フラグ
- `order` - 表示順序

## デプロイ

### Railwayでのデプロイ

1. **Build Command**
```
composer install --optimize-autoloader --no-dev && npm install && npm run build
```

2. **Start Command**
```
php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public public/index.php
```

3. **環境変数**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
DB_CONNECTION=pgsql
```

## ライセンス

このプロジェクトはMITライセンスの下で公開されています。

## 作者

- **森** - 開発者

## 更新履歴

- **v1.2.0** - UI/UX改善・ワークフロー機能強化（2025/08/21）
  - **フェーズ単位ワークフロー機能**
    - フェーズ単位での提出・レビュー・承認・差戻し機能
    - フェーズPMOコメント履歴表示機能
    - フェーズコメント履歴テーブル（project_phase_feedbacks）追加
  - **チェックリスト項目の個別フィードバック機能**
    - チェックリスト項目ごとのPMOコメント履歴表示
    - 差戻し時の個別項目選択・コメント機能
    - チェックリストフィードバック履歴テーブル（checklist_feedbacks）追加
  - **UI/UX改善**
    - 進捗バーの削除（完了判定はPMO承認による）
    - チェックリスト項目のステータス表示改善（「下書き」非表示、「差戻し」は前回PMOがチェックした項目のみ）
    - プロジェクト一覧画面の編集ボタン削除
    - 削除ボタンの権限制限（自分が作成したプロジェクトのみ）
    - チェックリスト項目削除権限をPMO/管理者のみに制限
  - **インライン編集機能**
    - チェックリスト項目の説明をインライン編集可能
    - 個別項目の提出・編集ボタン削除
  - **システム改善**
    - タイムゾーンを日本時刻（Asia/Tokyo）に変更
    - Railwayでの自動マイグレーション実行設定
    - メールアドレスをオプション化（ユーザーID/パスワード認証）
  - **データベース構造改善**
    - プロジェクトテーブルにフェーズワークフロー関連フィールド追加
    - ユーザーテーブルのメールアドレスをnullable化

- **v1.1.0** - 認証・ユーザー管理機能追加
  - ユーザー認証機能（Laravel Breeze）
  - ロールベースアクセス制御
  - ユーザー管理機能（管理者のみ）
  - セキュリティ強化

- **v1.0.0** - 初期リリース
  - プロジェクト管理機能
  - チェックリスト機能
  - 進捗管理機能
