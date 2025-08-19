@extends('layouts.app')

@section('title', 'プロジェクト編集')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                プロジェクト編集
            </h2>
        </div>
    </div>

    <form action="https://pmosystem-production.up.railway.app/projects/{{ $project->id }}" method="POST" class="mt-8 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">プロジェクト情報</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        プロジェクトの基本情報を編集してください。
                    </p>
                </div>
                <div class="mt-5 md:col-span-2 md:mt-0">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">プロジェクト名 *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="health" class="block text-sm font-medium text-gray-700">進捗ヘルス *</label>
                            <select name="health" id="health" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('health') border-red-300 @enderror">
                                <option value="Green" {{ old('health', $project->health) == 'Green' ? 'selected' : '' }}>Green（良好）</option>
                                <option value="Amber" {{ old('health', $project->health) == 'Amber' ? 'selected' : '' }}>Amber（注意）</option>
                                <option value="Red" {{ old('health', $project->health) == 'Red' ? 'selected' : '' }}>Red（危険）</option>
                            </select>
                            @error('health')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">顧客名 *</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $project->customer_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('customer_name') border-red-300 @enderror">
                            @error('customer_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="pm_name" class="block text-sm font-medium text-gray-700">PM名 *</label>
                            <input type="text" name="pm_name" id="pm_name" value="{{ old('pm_name', $project->pm_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('pm_name') border-red-300 @enderror">
                            @error('pm_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="priority" class="block text-sm font-medium text-gray-700">優先度 *</label>
                            <select name="priority" id="priority" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('priority') border-red-300 @enderror">
                                <option value="High" {{ old('priority', $project->priority) == 'High' ? 'selected' : '' }}>High（高）</option>
                                <option value="Medium" {{ old('priority', $project->priority) == 'Medium' ? 'selected' : '' }}>Medium（中）</option>
                                <option value="Low" {{ old('priority', $project->priority) == 'Low' ? 'selected' : '' }}>Low（低）</option>
                            </select>
                            @error('priority')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="phase" class="block text-sm font-medium text-gray-700">フェーズ *</label>
                            <select name="phase" id="phase" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('phase') border-red-300 @enderror">
                                <option value="planning" {{ old('phase', $project->phase) == 'planning' ? 'selected' : '' }}>企画</option>
                                <option value="requirements" {{ old('phase', $project->phase) == 'requirements' ? 'selected' : '' }}>要件</option>
                                <option value="design" {{ old('phase', $project->phase) == 'design' ? 'selected' : '' }}>設計</option>
                                <option value="implementation" {{ old('phase', $project->phase) == 'implementation' ? 'selected' : '' }}>実装</option>
                                <option value="testing" {{ old('phase', $project->phase) == 'testing' ? 'selected' : '' }}>テスト</option>
                                <option value="release" {{ old('phase', $project->phase) == 'release' ? 'selected' : '' }}>リリース</option>
                                <option value="operation" {{ old('phase', $project->phase) == 'operation' ? 'selected' : '' }}>運用</option>
                            </select>
                            @error('phase')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="budget" class="block text-sm font-medium text-gray-700">予算</label>
                            <input type="number" name="budget" id="budget" value="{{ old('budget', $project->budget) }}" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('budget') border-red-300 @enderror">
                            @error('budget')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="baseline_start_date" class="block text-sm font-medium text-gray-700">計画開始日</label>
                            <input type="date" name="baseline_start_date" id="baseline_start_date" value="{{ old('baseline_start_date', $project->baseline_start_date ? $project->baseline_start_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('baseline_start_date') border-red-300 @enderror">
                            @error('baseline_start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="baseline_end_date" class="block text-sm font-medium text-gray-700">計画終了日</label>
                            <input type="date" name="baseline_end_date" id="baseline_end_date" value="{{ old('baseline_end_date', $project->baseline_end_date ? $project->baseline_end_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('baseline_end_date') border-red-300 @enderror">
                            @error('baseline_end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="actual_start_date" class="block text-sm font-medium text-gray-700">実績開始日</label>
                            <input type="date" name="actual_start_date" id="actual_start_date" value="{{ old('actual_start_date', $project->actual_start_date ? $project->actual_start_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('actual_start_date') border-red-300 @enderror">
                            @error('actual_start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="actual_end_date" class="block text-sm font-medium text-gray-700">実績終了日</label>
                            <input type="date" name="actual_end_date" id="actual_end_date" value="{{ old('actual_end_date', $project->actual_end_date ? $project->actual_end_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('actual_end_date') border-red-300 @enderror">
                            @error('actual_end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="deliverables_summary" class="block text-sm font-medium text-gray-700">成果物概要</label>
                            <textarea name="deliverables_summary" id="deliverables_summary" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('deliverables_summary') border-red-300 @enderror">{{ old('deliverables_summary', $project->deliverables_summary) }}</textarea>
                            @error('deliverables_summary')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">主要リンク</label>
                            <div class="space-y-2">
                                <input type="url" name="main_links[]" placeholder="Backlog/Issue URL" value="{{ old('main_links.0', $project->main_links[0] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <input type="url" name="main_links[]" placeholder="Gitリポジトリ URL" value="{{ old('main_links.1', $project->main_links[1] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <input type="url" name="main_links[]" placeholder="社内Wiki URL" value="{{ old('main_links.2', $project->main_links[2] ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('projects.show', $project) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                キャンセル
            </a>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                更新
            </button>
        </div>
    </form>
</div>
@endsection
