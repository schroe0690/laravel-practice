<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出勤簿 - 勤怠管理システム</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/attendance.css', 'resources/js/stamp.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">出勤簿</h1>
                <div class="flex space-x-4">
                    <a href="/stamp-view" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        打刻画面へ
                    </a>
                    <a href="/" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                        ホームへ
                    </a>
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-700">
                    {{ now()->format('Y年m月') }}の勤務記録
                </h2>
            </div>

            @if($stampHistoryOneMonth->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日付</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">出勤</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">休憩開始</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">休憩終了</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">退勤</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">勤務時間</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $groupedStamps = $stampHistoryOneMonth->groupBy(function($stamp) {
                                    return $stamp->stamp_time->format('Y-m-d');
                                });

                                // 現在の月の最初の日と最後の日を取得
                                $currentMonth = now()->format('Y-m');
                                $firstDay = \Carbon\Carbon::parse($currentMonth . '-01');
                                $lastDay = $firstDay->copy()->endOfMonth();

                                // 一ヶ月分の日付を生成
                                $allDates = [];
                                $currentDate = $firstDay->copy();
                                while ($currentDate->lte($lastDay)) {
                                    $allDates[] = $currentDate->format('Y-m-d');
                                    $currentDate->addDay();
                                }
                            @endphp

                            @foreach($allDates as $date)
                                @php
                                    $stamps = $groupedStamps->get($date, collect());
                                    $clockIn = $stamps->where('stamp_type', \App\Enums\StampType::CLOCK_IN)->first();
                                    $clockOut = $stamps->where('stamp_type', \App\Enums\StampType::CLOCK_OUT)->first();
                                    $breakIn = $stamps->where('stamp_type', \App\Enums\StampType::BREAK_IN)->first();
                                    $breakOut = $stamps->where('stamp_type', \App\Enums\StampType::BREAK_OUT)->first();

                                    // 勤務時間の計算
                                    $workingTime = null;
                                    if ($clockIn && $clockOut) {
                                        // 出勤時間が退勤時間より前の場合のみ計算
                                        if ($clockIn->stamp_time->lt($clockOut->stamp_time)) {
                                            $totalTime = $clockIn->stamp_time->diffInMinutes($clockOut->stamp_time);
                                            $breakTime = 0;
                                            if ($breakIn && $breakOut) {
                                                // 休憩時間が正しい順序（開始 < 終了）の場合のみ計算
                                                if ($breakIn->stamp_time->lt($breakOut->stamp_time)) {
                                                    $breakTime = $breakIn->stamp_time->diffInMinutes($breakOut->stamp_time);
                                                }
                                            }
                                            $workingTime = $totalTime - $breakTime;
                                            // 負の値の場合は0に設定
                                            if ($workingTime < 0) {
                                                $workingTime = 0;
                                            }
                                        } else {
                                            // 出勤時間が退勤時間より後の場合は0
                                            $workingTime = 0;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="date-cell">
                                        {{ \Carbon\Carbon::parse($date)->format('m/d (D)') }}
                                    </td>
                                    <td class="time-cell">
                                        {{ $clockIn ? $clockIn->stamp_time->format('H:i') : '-' }}
                                    </td>
                                    <td class="time-cell">
                                        {{ $breakIn ? $breakIn->stamp_time->format('H:i') : '-' }}
                                    </td>
                                    <td class="time-cell">
                                        {{ $breakOut ? $breakOut->stamp_time->format('H:i') : '-' }}
                                    </td>
                                    <td class="time-cell">
                                        {{ $clockOut ? $clockOut->stamp_time->format('H:i') : '-' }}
                                    </td>
                                    <td class="working-time-cell">
                                        @if($workingTime !== null)
                                            {{ sprintf('%02d:%02d', floor($workingTime / 60), $workingTime % 60) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>今月の勤務記録がありません。</p>
                    <a href="/stamp-view" class="empty-state-link">
                        打刻を開始する
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
