<?php
function addHelpRequest($hType, $content, $urgency, $user_id, $idAdr): true|string
{
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
function addAddress($city, $postal_code, $street, $homeN): true|string
{
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
function require_admin(): void
{
    if (!is_logged_in() || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
        header("Location: index.php?page=login");
        exit;
    }
}
function getAllUserInfo($userId): array|string
{
    try{
    global $db;
    $stmt = $db->prepare("SELECT * FROM User
                                JOIN defUser ON User.user_id = defUser.user_id
                                JOIN Address ON defUser.idAdr = Address.idAdr
                                LEFT JOIN HelpRequests ON defUser.idAdr = HelpRequests.idAdr
                                WHERE defUser.user_id = :id "
                        );
    $stmt->execute([
        "id" => $userId
    ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
function dateformatter($date): string
{
    $dateString = $date ?? 'now';
    $date = new DateTime($dateString);

    $month = [
        1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];

    $monthName = $month[(int)$date->format('n')];
    $year = $date->format('Y');
    return $monthName . " " . $year;
}
function getNumberHelpRequestsByUser($userId): int
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT COUNT(*) as count  FROM HelpRequests WHERE inNeed_id = :id");
        $stmt->execute([
            "id" => $userId
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return (int)$result[0]['count'];
    }catch (Exception $e) {
        return 0;
    }
}
function getNumberCompletedRequestsByUser($userId): int{
    global $db;
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as count  FROM Help WHERE volunteer_id = :id");
        $stmt->execute([
            "id" => $userId
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return (int)$result[0]['count'];
    }catch(Exception $e){
        return 0;
    }
}
function deleteUser($userId) {
    global $db;
    try{
        $stmt = $db->prepare("DELETE FROM User WHERE user_id = :id");
        $stmt->execute([
            "id" => $userId
        ]);
        return true;
    }
    catch(PDOException $e){
        return false;
    }
}
function deleteHelpRequest($helpRequestId) {
    global $db;
    try{
        $stmt = $db->prepare("DELETE FROM HelpRequests WHERE idR = :id");
        $stmt->execute([
            "id" => $helpRequestId
        ]);
        return true;
    } catch (PDOException $e){
        return false;
    }
}