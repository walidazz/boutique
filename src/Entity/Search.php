<?php

namespace App\Entity;

use App\Entity\Category;

class Search 
{

/**
 * Undocumented variable
 * @var string
 */
private $string;

/**
 * @var Category[]
 */
private $category = [];








/**
 * Get undocumented variable
 *
 * @return  string
 */ 
public function getString()
{
return $this->string;
}

/**
 * Set undocumented variable
 *
 * @param  string  $string  Undocumented variable
 *
 * @return  self
 */ 
public function setString(string $string)
{
$this->string = $string;

return $this;
}

/**
 * Get the value of category
 *
 * @return  Category[]
 */ 
public function getCategory()
{
return $this->category;
}

/**
 * Set the value of category
 *
 * @param  Category[]  $category
 *
 * @return  self
 */ 
public function setCategory(Array $category)
{
$this->category = $category;

return $this;
}
}