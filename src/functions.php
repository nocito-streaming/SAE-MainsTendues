<?php
function addHelpRequest($hType, $content, $urgency, $user_id, $idAdr){
    global $db ;
    try {
        $stmt = $db->prepare("INSERT INTO HelpRequests(hType , content , status , urgencyLevel, idAdr, inNeed_id) 
                                     VALUES (:hType, :content , :status , :urgencyLevel,:idAdr, :inNeed_id)");
        $stmt->execute([
            "hType" => $hType,
            "content" => $content,
            "status" => "open",
            "urgencyLevel" => $urgency,
            "idAdr" => $idAdr,
            "inNeed_id" => $user_id,
        ]);
        return true;
    } catch (Exception $e) {
        return "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
function addAddress($city, $postal_code, $street, $homeN){
    global $db;
    try{
        $stmt = $db->prepare("INSERT INTO Address(city, postalCode, street, homeN) 
                                       VALUES (:city, :postal_code, :street, :homeN)");
        $stmt->execute([
            'city' => $city,
            'postal_code' => $postal_code,
            'street' => $street,
            'homeN' => $homeN
        ]);
        return true;
    } catch (Exception $e) {
        return "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
function require_admin() {
    if (!is_logged_in() || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
        header("Location: index.php?page=login");
        exit;
    }
}
?>
