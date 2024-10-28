<?php
namespace App\Repository\Interfaces;


use Illuminate\Support\Collection;

interface CipherRepositoryInterface
{
   public function all(): Collection;
}