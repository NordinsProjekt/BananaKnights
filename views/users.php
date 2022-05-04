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
    $text .= "<tr> <td></td><td><input type='submit' id='btnRegisterUser 'name='RegisterUser' class='btn btn-outline-primary' value='Registrera konto' /></td></tr>";
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
    $text .= "<tr> <td></td><td><input type='submit' id='btnLoginUser' class='btn btn-outline-primary' name='LoginUser' value='Logga in' /></td></tr>";
    return $text;
}


function Profile($user, $userDetails, $userInfo,$window)
{
    $text = "";
    $text.= "<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet'>";
    $text.= "<link href='".prefix."/css/style.css' rel='stylesheet'>";
    $text.= "<div class='container mt-5'>";
    $text.= "    <div class='row'>";
    $text.= "        <div class='col-xs-12 col-md-4 col-lg-3'>";
    $text.= "            <div class='userProfileInfo'>";
    $text.= "                <div class='image text-center'>";
    $text.= "                    <img src='".prefix."img/profile/noimage.png' alt='#' class='img-responsive'>";
    $text.= "                </div>";
    $text.= "                <div class='box' style='color: black;'>";
    $text.= "                    <div class='name'><strong>".$userDetails['UserName']."</strong></div>";
    $text.= "                    <div class='info'>";
    $text.= "                        <a style='padding:0;' href=''>".$userDetails['PhoneNumber']."</a><br>";
    $text.= "                        <a style='padding:0;' href=''>".$userDetails['Email']."</a><br>";
    $text.= "                        <span>".$userInfo['Address']."<br>".$userInfo['PostalCode'].", ".$userInfo['City']."</span>";
    $text.= "                    </div>";
    $text.= "                </div>";
    $text.= "            </div>";
    $text.= "        </div>";
    $text.= "        <div class='col-xs-12 col-md-8 col-lg-9' style='color: black;'>";
    $text.= "           <div class='box'>";
    $text.="            <div><a href='".prefix."user/profile'>Hem</a><a href='".prefix."user/profile?show=readlist'>Läslista</a><a href='".prefix."user/profile?show=reviews'>Mina reviews</a>
                            <a href='".prefix."user/profile?show=userinfo'>Personuppgifter</a></div><hr>";
    $text.= "               <h2 class='boxTitle'>".$window['WindowTitle']."</h2>
                            ".$window['Body'].""; //Här ska listor läggas
    $text.= "            </div>";
    $text.= "        </div>";
    $text.= "    </div>";
    $text.= "</div>";

    return $text;
}
function UserInformationForm($user,$userInfo)
{
    $formId = uniqid($user['Id'],true);
    //Säkerhetstest, sparar formuläretsdata i session så den inte kan editeras
    $_SESSION['form'][$formId] = array ( "FormAction"=>prefix."user/updateinfo",
    "UserId"=>$user['Id']);
    $text = "";
    $text .= "<form method='post'>";
    $text .= "<table><tr> <th></th> <th></th> </tr>";
    $text .= "<tr> <td><label for='firstname'>Förnamn</label></td> <td><input type='text' class='form-control' id='firstname' name='firstname' value='".$userInfo['Firstname']."' /></td> </tr>";
    $text .= "<tr> <td><label for='lastname'>Efternamn</label></td> <td><input type='text' class='form-control' id='lastname' name='lastname' value='".$userInfo['Lastname']."' /></td> </tr>";
    $text .= "<tr> <td><label for='phone'>Telefon</label></td> <td><input type='text' class='form-control' id='phone' name='phone' value='".$userInfo['Phone']."'  /></td> </tr>";
    $text .= "<tr> <td><label for='address'>Adress</label></td> <td><input type='text' class='form-control' id='address' name='address' value='".$userInfo['Address']."'  /></td> </tr>";
    $text .= "<tr> <td><label for='address2'>Adress 2</label></td> <td><input type='text' class='form-control' id='address2' name='address2' value='".$userInfo['Address2']."'  /></td> </tr>";
    $text .= "<tr> <td><label for='postalcode'>Postnummer</label></td> <td><input type='text' class='form-control' id='postalcode' name='postalcode' value='".$userInfo['PostalCode']."'  /></td> </tr>";
    $text .= "<tr> <td><label for='city'>Stad</label></td> <td><input type='text' class='form-control' id='city' name='city' value='".$userInfo['City']."' /></td> </tr>";
    $text .= "<tr><td><input type='hidden' name='formname' value='".$formId."' /><input type='submit' class='btn btn-outline-primary' value='Spara' /></td> <td></td></tr>";
    $text .= "</table></form>";
    return $text;
}
?>