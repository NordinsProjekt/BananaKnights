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
    <td><input type='text' class='form-control' id='txtUsername'name='Username' pattern='.{4,}' placeholder='Minst 4 tecken' required /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' class='form-control' id='txtPassword'name='Password' pattern='.{8,}' placeholder='Minst 8 tecken' required /></td></tr>";
    $text .= "<tr> <td><label for='txtConfirmPassword' />Bekräfta lösenord: </label></td>
    <td><input type='password' class='form-control' id='txtConfirmPassword' name='ConfirmPassword' pattern='.{8,}' placeholder='Minst 8 tecken' required /></td></tr>";
    $text .= "<tr> <td><label for='txtEmail' />Email </label></td>
    <td><input type='email' class='form-control' id='txtEmail'name='Email' pattern='.{5,}' placeholder='ex user@gmail.com' required /></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnRegisterUser 'name='RegisterUser' class='btn btn-primary' value='Registrera konto' /></td></tr>";
    return $text;
}

function LoginForm()
{
    $text ="<h1>Logga in</h1>";
    $text .= "<form method='post' action='".prefix."user/loginuser'><table>";
    $text .= "<tr> <th></th><th></th> </tr>";
    $text .= "<tr> <td><label for='txtUsername' />Användarnamn </label></td>
    <td><input type='text' class='form-control' id='txtUsername' name='Username' required /></td></tr>";
    $text .= "<tr> <td><label for='txtPassword' />Lösenord </label></td>
    <td><input type='password' class='form-control' id='txtPassword'name='Password' required /></td></tr>";
    $text .= "<tr> <td></td><td><input type='submit' id='btnLoginUser' class='btn btn-primary' name='LoginUser' value='Logga in' /></td></tr>";
    return $text;
}
?>