<?php

function SignUpForm($message)
{
    $text ="<h1>Skapa Användarkonto (Vanlig användare)</h1>";
    if ($message != "")
    {
        $text.= "<p>" . $message . "</p>";
    }
    $text .= "<form method='post' action='SaveUser'><table>";
    $text .= "<tr> <th></th><th></th> </tr>";
    $text .= "<tr> <td><label for='txtUsername' />Användarnamn </label></td>
    <td><input type='text' id='txtUsername'name='Username' /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' id='txtPassword'name='Password' /></td></tr>";
    $text .= "<tr> <td><label for='txtConfirmPassword' />Bekräfta lösenord: </label></td>
    <td><input type='password' id='txtConfirmPassword'name='ConfirmPassword' /></td></tr>";
    $text .= "<tr> <td><label for='txtEmail' />Email </label></td>
    <td><input type='text' id='txtEmail'name='Email' /></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnRegisterUser 'name='RegisterUser' value='Registrera konto' /></td></tr>";
    return $text;
}

function LoginForm($message)
{
    $text ="<h1>Logga in</h1>";
    if ($message != "")
    {
        $text.= "<p>" . $message . "</p>";
    }
    $text .= "<form method='post' action='loginuser'><table>";
    $text .= "<tr> <th></th><th></th> </tr>";
    $text .= "<tr> <td><label for='txtUsername' />Användarnamn </label></td>
    <td><input type='text' id='txtUsername'name='Username' /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' id='txtPassword'name='Password' /></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnLoginUser 'name='LoginUser' value='Logga in' /></td></tr>";
    return $text;
}

?>