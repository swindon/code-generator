# Random Code Generator

[<img src="https://exceloffthegrid.com/wp-content/uploads/2020/02/bmc-button.png" alt="Buy me a coffee" width="200"/>](https://www.buymeacoffee.com/scottwindon)

## Install:
`composer require swindon/code-generator`

## Usage:

### Single Code Generation:
```php
/**
 * Generates a random code
 *
 * @param   $length             int
 * @return string
 */
->generate(int $length = 8) : string
```

**Example #1**
Generates 12 character long string
```php
(new CodeGenerator)->generate(12);
// output: 4CF9O7XP
```

**Example #2**
Generates 8 character long string, with alpha characters only (lower and upper case)
```php
(new CodeGenerator)->setCharacters(CodeGenerator::CHAR_ALPHA)->generate(8);
// output: JTFRujQJ
```

**Example #3**
Generates 10 character long string, with all characters and symbols, removing ambiguous characters (eg S, 5, 0, O etc.)
```php
(new CodeGenerator)->setCharacters(CodeGenerator::CHAR_ALL)->generate(10);
// output: J\TTu@<ab4
```

**Example #4**
Generates 6 character long string, using only characters stated
```php
(new CodeGenerator)->setCharacters('ABCXYZabcxyz')->generate(6);
// output: XcZxbc
```

---

### Batch Code Generation:
```php
/**
 * Generates an array of random codes
 *
 * @param   $maxNum             int
 * @param   $length             int
 * @return  array
 */
->bulk(int $maxNum, int $length = 8) : array
```

**Example #1**
Generates array of 6 x 8 character long unique codes
```php
(new CodeGenerator)->bulk(6)
// output: [
//   "d3MEJqNq",
//   "Dr4mYKxP",
//   "J0RqiCJ9",
//   "NZuaPUVC",
//   "aZgMjn2m",
//   "EnyQKrat",
// ];
```

**Example #2**
Generates array of 6 x 6 character long unique codes appended to original array of codes
```php
(new CodeGenerator)->bulk(3, 6)
// output: [
//   "OsphQo",
//   "LPKCs2",
//   "DCv0QS",
// ];
```

**Example #3**
Generates array of 6 x 8 character long unique codes, using only characters stated
```php
(new CodeGenerator)->setCharacters('ABCXYZabcxyz')->bulk(6)
// output: [
//   "xcXAcYbb",
//   "YZXAYBBX",
//   "ZZzYxZyy",
//   "ZYZacACa",
//   "BYzzBYaB",
//   "xZxbacAz",
// ];
```

---

## Exceptions

### CodeGeneratorException
The CodeGeneratorException can be thrown for multiple reasons, on main reason for this will be from the bulk generation method.
If the amount to codes required to be generated exceeds the amount which can be generated (based on character set and code length) then an exception will be thrown.

**Example**
Try generating array of 100 x 3 character long unique codes, using only characters stated
```php
(new CodeGenerator)->setCharacters('ABC')->bulk(100, 3);
// throws: 'Cannot generate more than 27 possible unique codes. Try increasing the code length.'
```

---

### Show some support!
[<img src="https://exceloffthegrid.com/wp-content/uploads/2020/02/bmc-button.png" alt="Buy me a coffee" width="200"/>](https://www.buymeacoffee.com/scottwindon)
