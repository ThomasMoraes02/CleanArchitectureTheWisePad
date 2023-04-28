<?php 
namespace CleanArchitecture\Entities;

interface Encoder
{
    public function encode(string $password): string;

    public function decode(string $password, string $hash): bool;

    public function __toString(): string;
}