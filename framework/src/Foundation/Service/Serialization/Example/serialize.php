<?php


/*
Example:
$user = new \App\Entity\User();
$user->setName('jean');
$user->setEmail('jeanyao@ymail.com');
$user->setPassword('secret');
Serializer::serialize('user.id', $user);


try {
    $context = Serializer::deserialize('user.id');
    dump($context);
} catch (\Exception $e) {
    dump($e->getMessage());
}
*/