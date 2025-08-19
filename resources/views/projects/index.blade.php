@extends('layouts.app')

@section('title', 'プロジェクト一覧')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">プロジェクト一覧</h1>
            <p class="mt-2 text-sm text-gray-700">登録されているプロジェクトの一覧です。</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
                新規プロジェクト
            </a>
        </div>
    </div>
    
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">進捗ヘルス</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">プロジェクト名</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">顧客名</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">PM名</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">優先度</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">フェーズ</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">実績開始日</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">実績終了日</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">操作</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($projects as $project)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->health_color_class }}">
                                        {{ $project->health_name }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->customer_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->pm_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->priority_color_class }}">
                                        {{ $project->priority_name }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $project->phase_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $project->actual_start_date ? $project->actual_start_date->format('Y/m/d') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($project->actual_end_date)
                                        {{ $project->actual_end_date->format('Y/m/d') }}
                                    @elseif($project->actual_start_date)
                                        進行中
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:text-blue-900 mr-4">編集</a>
                                    <form action="https://pmosystem-production.up.railway.app/projects/{{ $project->id }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('本当に削除しますか？')">
                                            削除
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                    プロジェクトが登録されていません。
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
