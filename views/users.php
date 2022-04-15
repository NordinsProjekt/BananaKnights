<?php

function SignUpForm($message)
{
    $text ="<h1>Skapa Användarkonto (Vanlig användare)</h1>";
    if ($message != "")
    {
        $text.= "<p>" . $message . "</p>";
    }
    $text .= "<form method='post' action='".prefix."user/SaveUser'><table>";
    $text .= "<tr> <th></th><th></th> </tr>";
    $text .= "<tr> <td><label for='txtUsername' />Användarnamn </label></td>
    <td><input type='text' id='txtUsername'name='Username' pattern='.{4,}' placeholder='Minst 4 tecken' /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' id='txtPassword'name='Password' pattern='.{8,}' placeholder='Minst 8 tecken' /></td></tr>";
    $text .= "<tr> <td><label for='txtConfirmPassword' />Bekräfta lösenord: </label></td>
    <td><input type='password' id='txtConfirmPassword'name='ConfirmPassword' pattern='.{8,}' placeholder='Minst 8 tecken' /></td></tr>";
    $text .= "<tr> <td><label for='txtEmail' />Email </label></td>
    <td><input type='email' id='txtEmail'name='Email' pattern='.{5,}' placeholder='ex user@gmail.com'/></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnRegisterUser 'name='RegisterUser' value='Registrera konto' /></td></tr>";
    return $text;
}

function LoginForm()
{
    $text ="<h1>Logga in</h1>";
    $text .= "<form method='post' action='".prefix."user/loginuser'><table>";
    $text .= "<tr> <th></th><th></th> </tr>";
    $text .= "<tr> <td><label for='txtUsername' />Användarnamn </label></td>
    <td><input type='text' id='txtUsername'name='Username' /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' id='txtPassword'name='Password' /></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnLoginUser 'name='LoginUser' value='Logga in' /></td></tr>";
    return $text;
}
?>