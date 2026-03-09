<h1>Page d'inscription</h1>
<nav>
    <h2>Créer un compte</h2>
    <h3>Veuillez remplir les informations suivantes pour créer votre compte :</h3>
    <form action="index.php?page=signUp" method="post">
        <label for="name">Prénom :</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="surname">Nom :</label>
        <input type="text" id="surname" name="surname" required><br><br>
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="tel">Numéro de téléphone :</label>
        <input type="tel" id="tel" name="tel" required><br><br>
        <label for="password">Mot de passe :</label>            
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm">Confirmer le mot de passe :</label>
        <input type="password" id="confirm" name="confirm" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
    <p>Vous avez déjà un compte ? <a href="index.php?page=login">Connectez-vous ici</a>.</p>
</nav>