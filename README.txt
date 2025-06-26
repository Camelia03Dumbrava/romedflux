
---

## Instalare [Link GitHub](https://github.com/Camelia03Dumbrava/romedflux.git)

### 1. Copiaza fiserele local 
- Clonează repository-ul local SAU
- Descarcă (Code > Download ZIP) și extrage arhiva zip 

### 2. Instalează XAMPP
- Descarcă și instalează XAMPP de pe site-ul oficial. [Link descărcare](https://www.apachefriends.org/index.html)
- Deschide **XAMPP Control Panel** și pornește:
  - `Apache`
  - `MySQL`

### 3. Creează baza de date
- Accesează `http://localhost/phpmyadmin`
- Creează o bază de date nouă (ex: `romedflux`)
- Importă structura și datele:
  - Mergi la tabul **Import**
  - Selectează fișierul `romedflux.sql` disponibil în repository sau arhivă
  - Importă fișierul

### 4. Copiază proiectul în htdocs
- Mută fișierele descărcate în acest folder: C:\xampp\htdocs\romedflux

### 5. Accesează aplicația [ROMED FLUX](http://localhost/romedflux/login.php)

---

## Probleme uzuale

### 1. Eroare la conectare MySql - Configurează fișierul de conexiune
- Deschide fișierul `config.php`
- Verifică și modifică dacă este cazul datele de conectare la baza de date:

```php
$host = 'localhost';
$db = 'romedflux';
$user = 'root';
$pass = ''; 
```

### 2. Pagina nu poate fi gasita

- Verifică în XAMPP Control Panel ca Apache și MySQL să fie pornite 
- Verifică ca fișierele să fie în locația precizată