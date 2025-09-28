<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stamp</title>
    @vite(['resources/css/stamp.css', 'resources/js/stamp.js'])
</head>
<body>
    <div class="stamp-container">

        <!-- タイトル -->
        <div class="stamp-header">
            <h1>打刻画面</h1>
            <div class="navigation-links">
                <a href="/attendance-record" class="nav-link">出勤簿を見る</a>
                <a href="/" class="nav-link">ホームへ</a>
            </div>
        </div>

        <!-- 打刻ボタン-->
        <div class="stamp-button-container">
            <form action="/stamp" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="across_dates" value="">
                <button type="submit" class="stamp-button">
                    <!-- 一度も打刻がない場合は "出勤" と表示 -->
                    {{ $last_stamp_type ? $last_stamp_type->actionLabel() : '出勤' }}
                </button>
            </form>
        </div>

        <!-- 現在の状態 -->
        <div class="stamp-status">
            <!-- 一度も打刻がない場合は "打刻履歴はありません" と表示 -->
            <h3>打刻状態：{{ $last_stamp_type ? $last_stamp_type->statusLabel() : '打刻履歴はありません' }}</h3>
        </div>

        <!-- 打刻の日時情報を表示 -->
        <div class="stamp-time-display">
            <!-- 現在の日時 -->
            <h2 class="current-time" id="current-time"></h2>
            <!-- 最後の打刻日時(一度も打刻がない場合は "打刻履歴はありません" と表示) -->
            <h2 class="last-stamp-time">{{ $last_stamp_type ? '前の打刻：' . $last_stamp_time : '打刻履歴はありません' }}</h2>
        </div>

    </div>

    <!-- 各変数をJavaScriptで利用できるようにする -->
    <script>
        window.lastStampType = @json($last_stamp_type);
        window.lastStampTime = @json($last_stamp_time);
    </script>

</body>
</html>
