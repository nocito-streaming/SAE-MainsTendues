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
function addUserToAdmin($userId){
    global $db;
    try{
        $stmt = $db->prepare("INSERT INTO Admin(user_id) VALUES(:user_id)");
        $stmt->execute([
            "user_id" => $userId
        ]);
        return true;
    } catch (PDOException $e){
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
function sendEmailFirstMessage($FirstMessage, $email, $req_title): bool{
    // TODO: Placer cette clé dans un fichier .env avant la mise en production
    $apiKey = 're_baXMsw6V_6We2nqRxoF31skj8sQABii7d';
    $emailData = [
        'from'    => 'contact@mainstendues.cloud-ip.cc',
        'to'      => [$email],
        'subject' => 'Nouvelle proposition d\'aide : ' . $req_title,
        'html'    => '
            <p><strong>Message de l\'intervenant :</strong></p>
            <blockquote style="border-left: 4px solid #ccc; padding-left: 10px;">'
            . $FirstMessage .
            '</blockquote>
        '
    ];

    $ch = curl_init('https://api.resend.com/emails');
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($emailData),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
    );
    curl_setopt_array($ch, $options);
    curl_setopt($ch, CURLOPT_PROXY, 'http://cache.univ-pau.fr:3128');
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    if ($httpCode == 200 || $httpCode == 201) {
        return true;
    } else {
        return false;
    }
};
