<?php
// 処理結果をviewへ渡すためのクラスです
class ResultController{

private int $resultNo;  //0:false, 1:true
private string $resultTitle; //処理結果のタイトル
private string $resultMsg; //処理結果のメッセージ
private string $linkUrl;  //戻るボタン表示時のリンクURL

public function __construct(int $no, string $title, string $msg, int $linkId) {
  $this->resultNo = $no;
  $this->resultTitle = $title;
  $this->resultMsg = $msg;
  $this->linkUrl = $this->getLinkUrl($linkId);
}

public function getResult(): array {
  $resultAry['resultNo'] = $this->resultNo;
  $resultAry['resultTitle'] = $this->resultTitle;
  $resultAry['resultMsg'] = $this->resultMsg;
  $resultAry['linkUrl'] = $this->linkUrl;
  return $resultAry;
}

private function getLinkUrl(int $linkId): string {
  $linkTxt = '';
  switch ($linkId) {
    case 0:
      $linkTxt = 'none'; break;
    case 1:
      $linkTxt = 'templateUser.php'; break;
    case 2:
      $linkTxt = 'main.php'; break;
    case 3:
      $linkTxt = 'ippinResult.php'; break;
    case 4:
      $linkTxt = 'contact.php'; break;
    case 5:
      $linkTxt = 'contactConfirm.php'; break;
    case 6:
      $linkTxt = 'login.php'; break;
    case 7:
      $linkTxt = 'templateAdmin.php'; break;
    case 8:
      $linkTxt = 'manageTop.php'; break;
    case 9:
      $linkTxt = 'foodsManagement.php'; break;
    case 10:
      $linkTxt = 'foodsEdit.php'; break;
    case 11:
      $linkTxt = 'foodsDeleteCheck.php'; break;
    case 12:
      $linkTxt = 'recipeManagement.php'; break;
    case 13:
      $linkTxt = 'recipeEdit.php'; break;
    case 14:
      $linkTxt = 'recipeDeleteCheck.php'; break;
    case 15:
      $linkTxt = 'linkCheck.php'; break;
    case 16:
      $linkTxt = 'error.php'; break;
  }
  return $linkTxt;

}


// public function setResult(int $no, string $title, string $msg){
//   $this->resultNo = $no;
//   $this->resultTitle = $title;
//   $this->resultMsg = $msg;
// }

// public function setResultNo(int $no){
//   $this->resultNo = $no;
// }

// public function setResultTitle(string $title){
//   $this->resultTitle = $title;
// }

// public function setResultMsg(string $msg){
//   $this->resultMsg = $msg;
// }

// public function getResultNo(): int {
//   return $this->resultNo;
// }

// public function getResulTitle(): string {
//   return $this->resultTitle;
// }

// public function getResultMsg(): string {
//   return $this->resultMsg;
// }



}