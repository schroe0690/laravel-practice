// --- 現在時刻の表示 ---

function updateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const timeString = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    document.getElementById('current-time').textContent = `現在時刻：${timeString}`;
}

// ページ読み込み時に時刻を表示
updateTime();

// 1秒ごとに時刻を更新
setInterval(updateTime, 1000);


// --- 日付を跨いだかの確認 ---

// 日付比較関数
function isDifferentDate(currentDate, lastStampTime) {
    if (!lastStampTime) {
        // 最後の打刻時刻がない場合は比較しない
        return false;
    }
    
    const current = new Date(currentDate);
    const last = new Date(lastStampTime);
    
    // 年、月、日を比較
    return current.getFullYear() !== last.getFullYear() ||
           current.getMonth() !== last.getMonth() ||
           current.getDate() !== last.getDate();
}

// フォーム送信前にアラートを表示
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="/stamp"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // 一旦送信を停止
            
            const button = form.querySelector('button[type="submit"]');
            const actionText = button.textContent.trim();
            
            const now = new Date();                     // 現在時刻を取得
            const lastStampTime = window.lastStampTime; // 最後の打刻日時
            const lastStampType = window.lastStampType; // 最後の打刻タイプ
            
            if (isDifferentDate(now, lastStampTime) && lastStampType !== 'clock_out') {
                // 日付が異なる「退勤」以外の打刻はアラート
                const lastDate = lastStampTime ? new Date(lastStampTime).toLocaleDateString('ja-JP') : 'なし';
                const currentDate = now.toLocaleDateString('ja-JP');
                
                if (!confirm(`日付が異なります。\n最後の打刻日: ${lastDate}\n今日の日付: ${currentDate}\n\n出勤しますか？`)) {
                    return; // キャンセルされた場合は処理を停止
                }

                // 隠しフィールドを作成または更新
                let acrossDatesInput = form.querySelector('input[name="across_dates"]');
                if (!acrossDatesInput) {
                    acrossDatesInput = document.createElement('input');
                    acrossDatesInput.type = 'hidden';
                    acrossDatesInput.name = 'across_dates';
                    form.appendChild(acrossDatesInput);
                }
                acrossDatesInput.value = true;
            } else if (!isDifferentDate(now, lastStampTime) && lastStampType === 'clock_out') {
                // 同日の退勤後の打刻はアラートを表示して打刻を完全に阻止
                alert(`退勤済みです。打刻できません。`);
                return; // 絶対に打刻を阻止
            }
            
            // 確認が取れた場合のみフォームを送信
            form.submit();
        });
    }
});