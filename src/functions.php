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
function change_password($user_id, $old_password, $new_password): true
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT pwdHash FROM User WHERE user_id = :user_id");
        $stmt->execute([
            "user_id" => $user_id]);
        $user = $stmt->fetch();
        if ($user && password_verify($old_password, $user['pwdHash'])) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE User SET pwdHash = :new_pwdHash WHERE user_id = :user_id");
            $stmt->execute([
                "new_pwdHash" => $new_hashed_password,
                "user_id" => $user_id
            ]);
            return true;
        }
    }catch (Exception $e){
        return "Erreur lors de l'enregistrement : " . $e->getMessage();}
}
function getUserInfoToChange($user_id) : array|string {
    global $db;
    try{
        $stmt = $db->prepare('SELECT * FROM User 
                                NATURAL JOIN defUser 
                                NATURAL JOIN Address 
                                WHERE user_id = :user_id');
        $stmt->execute([
            "user_id" => $user_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e){
        return "Erreur lors de recuperation des donnees : " . $e->getMessage();
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
function getUserAddress($userId): array|string
{
    try{
    global $db;
    $stmt = $db->prepare("SELECT Address.* FROM defUser 
                                JOIN Address ON defUser.idAdr = Address.idAdr
                                WHERE defUser.user_id = :id");
    $stmt->execute([
        "id" => $userId
    ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Erreur lors de l'enregistrement : " . $e->getMessage();
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

function sendEmailFirstMessage($FirstMessage, $email, $req_title, $req_firstName): bool{
    // TODO: Placer cette clé dans un fichier .env avant la mise en production
    $apiKey = 're_baXMsw6V_6We2nqRxoF31skj8sQABii7d';
    
    $safeMessage = nl2br(htmlspecialchars($FirstMessage));
    $safeTitle = htmlspecialchars($req_title);
    $safeName = htmlspecialchars($req_firstName);

    // Construction de l'email HTML "Anti-Spam"
    $htmlContent = '
    <div style="font-family: Arial, sans-serif; color: #333333; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
        <p>Bonjour <strong>' . $safeName . '</strong>,</p>
        <p>J\'espère que vous allez bien.</p>
        <p>Vous recevez ce message car vous avez publié une demande d\'aide (<strong>' . $safeTitle . '</strong>) sur la plateforme <em>Mains Tendues</em>.</p>
        
        <p>Une personne formidable vient de vous envoyer une proposition d\'aide ! Voici son message :</p>
        
        <blockquote style="border-left: 4px solid #4CAF50; background-color: #f9f9f9; padding: 15px; margin: 20px 0; font-style: italic; border-radius: 4px;">
            ' . $safeMessage . '
        </blockquote>
        
        <p>Pour lui répondre et organiser votre échange, veuillez vous connecter à votre compte :</p>
        
        <div style="text-align: center; margin: 35px 0;">
            <a href="https://mains-tendues.fr/index.php?page=login" style="background-color: #2563eb; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Voir ma messagerie</a>
        </div>
        
        <p>À très vite,<br><strong>L\'équipe Mains Tendues</strong></p>
        
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #888888; text-align: center;">
            Vous recevez cet e-mail car vous êtes inscrit(e) sur Mains Tendues.<br>
            Si vous avez trouvé de l\'aide ou souhaitez fermer cette demande, <a href="https://mains-tendues.fr/index.php?page=myRequests" style="color: #888888; text-decoration: underline;">gérez vos annonces ici</a>.
        </p>
    </div>';

    $emailData = [
        'from'    => 'Mains Tendues <contact@mains-tendues.fr>', // Ajout du nom de l'expéditeur
        'to'      => [$email],
        'subject' => 'Quelqu\'un propose de vous aider pour : ' . $safeTitle, // Objet plus naturel
        'html'    => $htmlContent
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
    curl_close($ch);
    
    return ($httpCode == 200 || $httpCode == 201);
}
