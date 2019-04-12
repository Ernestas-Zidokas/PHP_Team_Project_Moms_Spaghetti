<?php

namespace Core\Abstracts;

abstract class Cookie {

    /**
     * Cookie pavadinimas
     * 
     * Jis naudojamas tiek nuskaitant duomenis iš
     * $_COOKIE, tiek funkcijoje setcookie
     * @var string 
     */
    protected $name;

    /**
     * Konstruktorius paprasčiausia turi nuset'tintis $name
     */
    abstract public function __construct(string $name);

    /**
     * Turi patikrinti ar cookie duotu pavadinimu
     * egzistuoja
     */
    abstract public function exists(): bool;

    /**
     * Turi return'inti json_decode'intą cookie'o
     * turinį. 
     * 
     * Patikrinti ar pavyko json_decode'inti
     * (Use Google)
     * Jei nepavyko, funkcija turi mesti warning'ą
     * (ne EXCEPTION'ą, bet WARNING'ą - Use Google).
     * ir return'inti tuščią array
     *  
     * Jei cookie'is nustatytu pavadinimu neegzistuoja,
     * turi return'inti tuščią array'ų
     */
    abstract public function read(): array;

    /**
     * Turi į Cookie duotu pavadinimu
     * išsaugoti json_encode'intą $data array'jų
     * (Google setcookie)
     * 
     * Į cookie galima įrašyt tik string'ą.
     * Kadangi mes norim galimybę turėti į tą patį
     * Cookie storinti daugiau data'os, galim tiesiog
     * encode'inti ir decode'inti array'jų su json'u.
     * 
     * Mes į cookie įrašysim už'json_encodinę $data
     * ir atkursim atgal json_decode'inę tai ką radom Cookie
     * 
     * @param $data array
     * @param $expires_in int Už kiek laiko sekundemis cookie nebegalios
     */
    abstract public function save(array $data, int $expires_in = 3600): void;

    /**
     * Turi ištrinti Cookie
     * (Use google)
     */
    abstract public function delete(): void;
    
}

