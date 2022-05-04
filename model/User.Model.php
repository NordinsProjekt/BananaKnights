<?php
require_once "classes/PDOHandler.class.php";
class UserModel extends PDOHandler
{
    function __destruct()
    {
        
    }
    public function GetAll()
    {
        $stmt = $this->Connect()->prepare("SELECT u.Id,UserName,Email,IFNULL(GROUP_CONCAT(r.Name),'') AS Roles FROM users AS u 
        LEFT JOIN usergroups AS ug ON u.Id = ug.UserId 
        LEFT JOIN roles AS r ON ug.RolesID = r.Id 
        GROUP BY u.Id");
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function GetAllLockedAccounts()
    {
        $stmt = $this->Connect()->prepare("SELECT u.Id,u.UserName,u.Email,IFNULL(GROUP_CONCAT(r.Name),'') AS Roles FROM users AS u 
        LEFT JOIN usergroups AS ug ON u.Id = ug.UserId 
        LEFT JOIN roles AS r ON ug.RolesID = r.Id 
        WHERE LockoutEnabled = 1 
        GROUP BY u.Id 
        ORDER BY u.UserName ASC;");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function GetAllRoles()
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM roles ORDER BY Name ASC;");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function GetAllRolesFromUser($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT r.Id,r.Name FROM roles AS r
        INNER JOIN usergroups AS ug ON r.Id = ug.RolesId 
        WHERE ug.UserId = :userId 
        ORDER BY r.Name ASC;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function GetUserRoles($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT u.Id,u.UserName,GROUP_CONCAT(r.Name) AS Roles FROM users AS u 
        INNER JOIN usergroups AS ug ON u.Id = ug.UserId 
        INNER JOIN roles AS r ON ug.RolesID = r.Id
        WHERE u.Id = :userId AND LockoutEnabled = 0;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetUserFromId($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT UserName FROM users WHERE Id = :userId");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetEntireUser($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM users WHERE Id = :userId");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetEntireUserInfo($userId)
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM userinfo WHERE Userid = :userId");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetUserID($username,$passwordhash)
    {
        $stmt = $this->Connect()->prepare("SELECT Id FROM users 
        WHERE UserName = :username AND PasswordHash = :passwordhash;");
        $stmt->bindParam(":username",$username,PDO::PARAM_STR);
        $stmt->bindParam(":passwordhash",$passwordhash,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetUserGroupID($groupname)
    {
        $stmt = $this->Connect()->prepare("SELECT Id from roles WHERE Name = :name;");
        $stmt->bindParam(":name",$groupname,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetUserFromUsername($username)
    {
        $stmt = $this->Connect()->prepare("SELECT * FROM users 
        WHERE UserName = :username;");
        $stmt->bindParam(":username",$username,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function UpdateFailedLogin($count, $userId)
    {
        $stmt = $this->Connect()->prepare("UPDATE users SET AccessFailedCount = :count WHERE Id = :userId;");
        $stmt->bindParam(":count",$count,PDO::PARAM_INT);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function UpdateLockedAccount($userId)
    {
        $stmt = $this->Connect()->prepare("UPDATE users SET LockoutEnabled = 1 WHERE Id = :userId;");
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function SetUser($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO users (Email,EmailConfirmed,PasswordHash,PhoneNumber,PhoneNumberConfirmed,
        TwoFactorEnabled,LockoutEndDateUtc,LockoutEnabled,AccessFailedCount,UserName) 
        VALUES (?,?,?,?,?,?,?,?,?,?);");
        $result = $stmt->execute($arr);
        return $result;
    }

    public function SetUserInformation($arr)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO userinfo VALUES (?,?,?,?,?,?,?,?,?,?);");
        $result = $stmt->execute($arr);
        return $result;
    }
    public function SetUserGroup($groupId,$userId)
    {
        $stmt = $this->Connect()->prepare("INSERT INTO usergroups (RolesId,UserId) 
        VALUES (:groupId,:userId);");
        $stmt->bindParam(":groupId",$groupId,PDO::PARAM_STR);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function RemoveRoleFromUser($groupId,$userId)
    {
        $stmt = $this->Connect()->prepare("DELETE FROM usergroups WHERE RolesId = :groupId 
        AND UserId = :userId;");
        $stmt->bindParam(":groupId",$groupId,PDO::PARAM_INT);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function DoesUserExist($username)
    {
        $stmt = $this->Connect()->prepare("SELECT COUNT(Id) AS NumberOfUsers FROM users 
        WHERE UserName = :username;");
        $stmt->bindParam(":username",$username,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function DoesUserHaveRole($roleName,$userId)
    {
        //Borde returnera 1 om användaren har den specifika rollen
        $stmt = $this->Connect()->prepare("SELECT COUNT(u.Id) AS NumberOfUsers FROM users AS u 
        INNER JOIN usergroups AS ug ON u.Id = ug.UserId 
        INNER JOIN roles AS r ON ug.RolesID = r.Id
        WHERE r.Name = :roleName AND u.Id = :userId;");
        $stmt->bindParam(":roleName",$roleName,PDO::PARAM_STR);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['NumberOfUsers'];
    }

    public function GetUserInformation()
    {
        
    }

    public function UpdateUserInput($arr)
    {
        $stmt = $this->Connect()->prepare("UPDATE userinfo SET Firstname = ?, Lastname = ?, Phone = ?,
        Address = ?, Address2 = ?, PostalCode = ?, City = ? WHERE UserId = ?;");
        return $stmt->execute($arr);
    }
}
?>