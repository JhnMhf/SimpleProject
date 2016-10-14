<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\AmburgerBundle\Entity;

use Doctrine\ORM\EntityRepository;
/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /* 
        Checks if the username exists and if the password is correct.
    */
    public function checkUser($username, $password){
        $user = $this->getUser($username);
        if(!$user){
            return -1;
        }
        if(!$this->checkPassword($username, $password)){
            return -2;
        }
        return $user;
    }
    /* 
        Returns the user if it exists.
    */
    public function getUser($username){
        return $this->findOneByName($username);
    }
    /* 
        Checks if the password is correct.
    */
    public function checkPassword($username, $password){
        $query = $this->createQueryBuilder('u')
            ->where('u.name = :username')
            ->setParameter('username', $username)
            ->getQuery();
        $user = $query->getOneOrNullResult();
        return password_verify($password, $user->getPassword());
    }
    
    public function isAdmin($userid){
        $query = $this->createQueryBuilder('u')
            ->where('u.id = :userid')
            ->setParameter('userid', $userid)
            ->getQuery();
        $user = $query->getOneOrNullResult();
        return $user->isAdmin();
    }
    
    public function updatePassword($userid, $newPassword){
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = $this->createQueryBuilder('u')
            ->where('u.id = :userid')
            ->setParameter('userid', $userid)
            ->getQuery();
        $user = $query->getOneOrNullResult();
        
        $user->setPassword($hashedPassword);
        
        $this->_em->persist($user);
        $this->_em->flush();
    }
    
    public function createNewUser($username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $newUser = new User();
        $newUser->setName($username);
        $newUser->setPassword($hashedPassword);
        
        $this->_em->persist($newUser);
        $this->_em->flush();
    }
}