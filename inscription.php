<?php
	session_start();

	//verification email, mdp
	if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two']))
	{
		require('src/connect.php');

		//VARIABLES
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		$password_two = htmlspecialchars($_POST['password_two']);

		//password = password_two ?
		if($password != $password_two)
		{
			header('location: inscription.php?error=1&message=Vos mots de passe ne sont pas identiques.');
			exit();
		}

		//adresse email valide ? 
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			header('location: inscription.php?error=1&message=Votre adresse email est invalide.');
			exit();
		}

		//email déjà utilisé ?
		$req = $db->prepare('SELECT COUNT(*) AS numberEmail FROM user WHERE email = ?');
		$req->execute(array($email));

		while($email_verification = $req->fetch())
		{
			if($email_verification['numberEmail'] != 0)
			{
				header('location: inscription.php?error=1&message=Votre adresse email est déjà utilisée par un autre utilisateur.');
				exit();
			}
		}

		//HASH
		$secret = sha1($email).time();
		$secret = sha1($secret).time();

		//chiffrage mdp
		$password = "aq1".sha1($password."123")."25";

		//Envoi
		$req = $db->prepare('INSERT INTO user(email, password, secret) VALUES(?,?,?)');
		$req->execute(array($email, $password, $secret));
		header('location: inscription.php?success=1');
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			
			<?php
			//Affichage des erreurs
				if(isset($_GET['error']))
				{
					if(isset($_GET['message']))
					{
						echo '<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
					}
				} else if(isset($_GET['success']))
				{
					echo '<div class="alert success">Vous êtes désormais inscrit. <a href="index.php">Connectez-vous</a>.</div>';
				}
			?>

			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>