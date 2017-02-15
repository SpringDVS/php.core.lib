<?php
use SpringDvs\Core\NetServices\Impl\CciKeyServiceModel;
use SpringDvs\Core\NetServices\Certificate;
use SpringDvs\Core\NetServices\Key;


$publicKeyFoobar = "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v2

mQENBFh2FzsBCADDTDI95cw4j96DNjVOIGmvUSOq7gmlfGXBD2qHaEHTLqV71odp
5I/SkHL2jbKVj4gI+CYWGtZoBFBNYnCos/A9mub33sUkhHqIYN00tedQjf5OAfnl
IytPE3TzR8VBUTdS771LNm6P6/lDAIsBtZ8wqyxaQL2aAf6Kg6yqm8OZjcXqRR2w
Uk4jsF0lYQ67R73duKx3XJFVd9OMxO9Qra/DrLTsFbNGNkaVogbaAcn4DQVnaYEA
ts6GRRwL8lchFsle8zVb7nE5f/Bo/1WimtCSk7GFeIBsBTUSUZfMuzUTHwMes4nE
R9cV3woZocWmvAN6aWPb51IWtXSBozBnH0sJABEBAAG0GEZvb2JhciA8Zm9vQGV4
YW1wbGUuY29tPokBNwQTAQgAIQUCWHYXOwIbAwULCQgHAgYVCAkKCwIEFgIDAQIe
AQIXgAAKCRDRk+zWMAhu0Y9yB/4zcmA+K+HzP4Tliws/K7++8wKkgcE7yvHMJjxg
h2Id8QJ9qZHjSS6JjpUUnVYnnKJxeByBAm4MU4+RT95noUqtfgN14dDbH/whspA3
EAA4q2q3IgkbI0HzuTEYORFgVvLNzSoP+atE7lhex2+XVwoz6aY9zx7zEBmx2XLR
fXc0LniK5vGEpuQtZkKSJ5tuQXz9EukINUzjVSwmxfGwi0LcxmKkd2xvDvjRZ3ld
SctXQ09BYert6lekccuoyekSSkB0f8BSNRSi7Uml2Meeh7gy8t7/L8gPuY+S+C4e
FjQ5i1R3FDcMKXG7B4gYALSp1JiRc7vJbT8/m904zbx+BhNCuQENBFh2FzsBCADx
00G3aAlaoQLhzzcg90PvXWTHS23h0k3zaqA0dJ8vekZwYUEUqY+nv4myMfK3xwRw
uad7Yuju5/b46f02b9gbwdqI/8iLanyDibITaVyPc4ZthbPDNpZtY6pLbLX1Oc9h
w7Kwhl+lQqoR4+NN6g0oyzEh67f4Pbo2f3gamTrnUHgikth4ZmWamxUKhhErVZ8j
93LcEYqf9uZVmxbAm6x0tP882fGjUsjHYVEKqw/MbLSeoaQ2HIdl3Rl/cn6hU2sY
c34wFR9i+Pv2m3blPjhf0jW8xyiY97UV+RBsbwPSIJ6yMlVj4efLMvYwcKH7qe5X
fHreVTB94f9vaKLG/5dBABEBAAGJAR8EGAEIAAkFAlh2FzsCGwwACgkQ0ZPs1jAI
btF/9gf+NtO6VRvLXJbzpz5M/DJTp5JQN0VgWUSVXNGNmvh3lxOgUDj33YVyERIs
3ayecJmk9PrezgMBcSYw6O/nFoMrJai5WqNuBQKHmlBCfKySd5F13rBDSXrFYu51
UVFDfw6+hC8r0OJ1qiefTkH4d33jY7dFE5pN2fxnJrhHa0DZA9UmHayJjmzd6+yE
B7+Z2EjKz0w0RT1qYgiSLNLyD78WmQgzJd5R1khoCqwijO7eeMuaBuR4nTcQSeQg
s6+/jVSI/MsUXSqX/R4rf8hqqWhUjsDXhHB2javdXJiNIGfVIrvt0EAfjp5owBmF
7IrC/ePmkL9vDabbuX66iCJ2cNxRvg==
=Hfkb
-----END PGP PUBLIC KEY BLOCK-----";

$publicKeyBarfoo = "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v2

mQENBFh2GGMBCAC8d4CWYLU52Ul6wqpblNWNBeTRie6vVXPDRGjY+Vta0YgH8UjG
X468NxLIAlo1zyzvIfEPDerU0NyolXDGwOxsjQGZcazcnbgagzTNhbPYsGlEHVIl
JE7t5mFs3/aE3AXUbedcf9d5RGfgmRgCI6abpN8Tdc0ekyxRyy/Kwe0JaCj3oLq9
xE2fdBcxENr53b4KTdvjgT2aouHjmyvnlZQ7GNkM2EK1Ls0vHQrABG+TR0uzzdEB
Y48Yx/DPWfpaAXNQZfikxQ8ZnOjthsjmyfj7mgTmtB5gjjaHm56Qx4VO1EgC6aPE
DWyNOZNbab4+WbCppl/v+q2kkDo9yx9/sFKJABEBAAG0GEJhcmZvbyA8YmFyQGV4
YW1wbGUuY29tPokBNwQTAQgAIQUCWHYYYwIbAwULCQgHAgYVCAkKCwIEFgIDAQIe
AQIXgAAKCRCsCTRH1viIn2JPCACHgZ2RfG8Kn0FFOgmXe6fwFaNx/UVye3ukZm3O
+g+SElSdnXIA53jhV5GH42LN+mmslP7snfaSiHG9BhTJDMSigU+HRSptD/Xf9vAK
TZ6Q3pN71e+cKxopjXk2Kh4k0y9D3LJkCcSEjR8Y3YY+tgZpxLZ3sv/Rwl87d6Tb
I7DtKE/3Vb7sj27TAlNxoqktxAdzztJj8CxTB7lt9NAJx3BMi3fcehoZljmFN/kQ
Wk+L3K3nDNk4R1cW+kneVbYf8a5F76R+zCV3MNQneeBfDtHWrxSmIOTEUHEGHT1E
ARpt/MqSxqXQ6OnWcF43TMYI44aklZAG+6uL7A+8vl2gS6LEuQENBFh2GGMBCADO
/ibQ1S8eRMlrtnhKexY5HsKH+slAj45RfikE4ft4i62ajxHYnklWq5EjLPetnYU6
U8ImWNiISzrYcZpElfElA/t9DO0pMErbmv56S0MgAMtjl15HzKQIfZlA03T6wXa3
4eIdrdxElnb+Fxt0WEUGvzvcROrysO15/koSKE0N3ZwElksm1L0BaPw/ZIPGpaUL
3ck0de1nMtc2lmvq7xvf+AdHu2v/YvqWc1ZLDOlRP7OcPlqnQ2Bxw1IhOc1n/MSr
QpGSaOt8aWX1NeHRmcKREhFqsf3GLUJs46R0u0iihk6aUZPrkx1tMWQgI1UVieTj
IU6J2i9Cd4owcg9FOl2ZABEBAAGJAR8EGAEIAAkFAlh2GGMCGwwACgkQrAk0R9b4
iJ8kRgf+KJwtwAvNJ8ceY6YptIH35BEKX50Ha8fozGUPzfjffBrTmhZDQZU1uhdC
XfBqAwNm2iOs+pd73Ncpy0JqpPoM8kS2CLYiu7HdCRHh0Dv1QrfvLSKmWtYal8dZ
CY5tfMzBcGtDAbUKoLgzjckinlN7X7QUpBcm6AUKK3ytC5WeaVU1lIyTFnrHj/g4
zBa6aOwlxuwQ2IeyfKDBNdFLXnr3ADAtnlXBdPEw29H++JwiEkgKKoBWpt+wbtuX
oMBhEg5SRb7BziPGPvgHTnwpYVG/t01MT77ASj6E1aPFDxnqlazkS1Sjq/2UOcnU
iErRToKFHjj1QskesNYb4dhYhl8vhQ==
=fKPg
-----END PGP PUBLIC KEY BLOCK-----";

$privateKeyFoobar = "-----BEGIN PGP PRIVATE KEY BLOCK-----
Version: GnuPG v2

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
heyKwv3j5pC/bw2m27l+uogidnDcUb4=
=OHgi
-----END PGP PRIVATE KEY BLOCK-----";



class CciKeyServiceModelTest
extends MockReady {
	
	/**
	 * @group online
	 * @group long
	 */
	public function testKeyGen() {
		$pks = new CciKeyServiceModel();
		
		$pair = $pks->generateKeyPair('foo', 'foo@example.com', 'pass');
		
		$this->assertNotNull($pair);
		$this->assertTrue(isset($pair['public']));
		$this->assertTrue(isset($pair['private']));
		$this->assertNotEquals('', $pair['public']);
		$this->assertNotEquals('', $pair['private']);
	}
	
	/**
	 * @group online
	 * @group long
	 */
	public function testExpandKey() {
		global $publicKeyBarfoo;
		$pks = new CciKeyServiceModel();
		$cert = $pks->expand(new Key($publicKeyBarfoo));	
		$this->assertNotNull($cert);
		$this->assertEquals('Barfoo', $cert->name());
		$this->assertEquals('bar@example.com', $cert->email());
		$this->assertEquals('ac093447d6f8889f', $cert->keyid());
		
		$sigs = $cert->signatures();
		$this->assertCount(1, $sigs);
		
		$this->assertEquals('ac093447d6f8889f', $sigs[0]->keyid);
	}
	
	/**
	 * @group online
	 * @group long
	 */
	public function testImportNoSubject() {
		global $publicKeyFoobar;
		$pks = new CciKeyServiceModel();
		$cert = $pks->expand(new Key($publicKeyFoobar));
		$this->assertNotNull($cert);
		$this->assertEquals('Foobar', $cert->name());
		$this->assertEquals('foo@example.com', $cert->email());
		$this->assertEquals('d193ecd630086ed1', $cert->keyid());
	
		$sigs = $cert->signatures();
		$this->assertCount(1, $sigs);
	
		$this->assertEquals('d193ecd630086ed1', $sigs[0]->keyid);
	}

	/**
	 * @group online
	 * @group long
	 */
	public function testSign() {
		global $publicKeyBarfoo;
		global $privateKeyFoobar;

		$pks = new CciKeyServiceModel();
		$key = $pks->sign(new Certificate($publicKeyBarfoo), new Key($privateKeyFoobar), 'passphrase');
		$this->assertNotNull($key);
	}
	
	/**
	 * @group online
	 * @group long
	 */
	public function testSignImport() {
		global $publicKeyBarfoo;
		global $privateKeyFoobar;
		
		$pks = new CciKeyServiceModel();
		$oldCertificate = new Certificate($publicKeyBarfoo);
		$newKey = $pks->sign($oldCertificate, new Key($privateKeyFoobar), 'passphrase');
		$this->assertNotNull($newKey);
		
		$actual = $pks->update($newKey, $oldCertificate);
		
		$this->assertEquals('Barfoo', $actual->name());
		$this->assertEquals('bar@example.com', $actual->email());
		$this->assertEquals('ac093447d6f8889f', $actual->keyid());
		
		$sigs = $actual->signatures();
		$this->assertCount(2, $sigs);
		
		$this->assertEquals('ac093447d6f8889f', $sigs[0]->keyid);
		$this->assertEquals('d193ecd630086ed1', $sigs[1]->keyid);
	}
}