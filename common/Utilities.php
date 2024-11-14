<?php

// 日時を取得
function getDatestr (): string {
    $date = new DateTime();
    $datestr = $date->format('Y-m-d H:i:s');
    return $datestr;
}

// foodIdをソート
function sortFoodIds(array $foodIds): string {
    $fValues = [];
    foreach ($foodIds as $x => $keys) {
        if (!empty($foodIds[$x])) {
            $fValues[] = $foodIds[$x];
        }
    }
    sort($fValues);
    $fValuesStr = implode('#',$fValues);
    $fValuesStr = "#{$fValuesStr}#";
    return $fValuesStr;
}

// ユーザー認証
// function certification($mailAddress, $password, $userFlag):bool  {
//     try {
//         $db = getDb();
//         $stt = $db->prepare("SELECT userId, nickName, password, userFlag FROM user WHERE mailAddress = '{$mailAddress}';");
//         $stt->execute();
//         if ($row = $stt->fetch(PDO::FETCH_ASSOC)) {
//             if ($row['password'] == $password) {
//                 if ($row['userFlag'] == $userFlag) {
//                     print '成功';
//                     return true;
//                 }
//                 else {
//                     print 'あなたは管理者じゃありません';
//                     return false;
//                     // $errorCode = ;
//                 }
//             }
//             else {
//                 print 'passwordが違います';
//                 return false;
//                 // $errorCode = ;
//             }
//         }
//         else {
//             print 'ユーザーが存在しません';
//             return false;
//             // $errorCode = ;
//         }
//     }
//     catch (PDOException $e) {
//         print 'dbで変';
//         return false;
//     }
// }