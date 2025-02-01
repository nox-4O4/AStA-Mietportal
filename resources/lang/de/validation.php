<?php

	return [
		/*
		|--------------------------------------------------------------------------
		| Validation Language Lines
		|--------------------------------------------------------------------------
		|
		| The following language lines contain the default error messages used by
		| the validator class. Some of these rules have multiple versions such
		| as the size rules. Feel free to tweak each of these messages.
		|
		*/

		'accepted'             => ':attribute muss akzeptiert werden.',
		'accepted_if'          => ':attribute muss akzeptiert werden wenn :other dem Wert :value entspricht.',
		'active_url'           => ':attribute ist keine gültige URL.',
		'after'                => ':attribute muss ein Datum nach dem :date sein.',
		'after_or_equal'       => ':attribute muss ein Datum nach dem :date oder gleich dem :date sein.',
		'alpha'                => ':attribute darf nur aus Buchstaben bestehen.',
		'alpha_dash'           => ':attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.',
		'alpha_num'            => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
		'ascii'                => ':attribute darf nur alphanumerische Zeichen und Symbole aus einzelnen Bytes enthalten.',
		'array'                => ':attribute muss ein Array sein.',
		'before'               => ':attribute muss ein Datum vor dem :date sein.',
		'before_or_equal'      => ':attribute muss ein Datum vor dem :date oder gleich dem :date sein.',
		'between'              => [
			'array'   => ':attribute muss zwischen :min und :max Elemente haben.',
			'file'    => ':attribute muss zwischen :min und :max Kilobytes groß sein.',
			'numeric' => ':attribute muss zwischen :min und :max liegen.',
			'string'  => ':attribute muss zwischen :min und :max Zeichen lang sein.',
		],
		'boolean'              => ":attribute muss entweder 'true' oder 'false' sein.",
		'can'                  => ':attribute enthält einen unzulässigen Wert.',
		'confirmed'            => ':attribute stimmt nicht überein.',
		'contains'             => 'Ein erforderliche Wert für :attribute fehlt.',
		'current_password'     => 'Das Passwort ist nicht korrekt.',
		'date'                 => ':attribute muss ein gültiges Datum sein.',
		'date_equals'          => ':attribute muss :date entsprechen.',
		'date_format'          => ':attribute entspricht nicht dem gültigen Format (:format).',
		'decimal'              => ':attribute muss über :decimal Dezimalstellen verfügen.',
		'declined'             => ':attribute muss abgelehnt werden.',
		'declined_if'          => ':attribute muss abgelehnt werden, wenn :other dem Wert :value entspricht.',
		'different'            => ':attribute und :other müssen sich unterscheiden.',
		'digits'               => ':attribute muss aus :digits Zahlen bestehen.',
		'digits_between'       => ':attribute muss zwischen :min und :max Stellen haben.',
		'dimensions'           => ':attribute hat ungültige Bildabmessungen.',
		'distinct'             => ':attribute beinhaltet einen bereits vorhandenen Wert.',
		'doesnt_end_with'      => ':attribute darf nicht mit einem der folgenden Werte enden: :values.',
		'doesnt_start_with'    => ':attribute darf nicht mit einem der folgenden Werte beginnnen: :values.',
		'email'                => ':attribute muss eine gütlige E-Mail-Adresse sein.',
		'ends_with'            => ':attribute muss mit einem der folgenden Werte enden: :values.',
		'enum'                 => ':attribute weist einen ungültigen Wert auf.',
		'exists'               => ':attribute muss ein gültiger Wert sein.',
		'extensions'           => ':attribute muss eine der folgenden Erweiterungen aufweisen: :values.',
		'file'                 => ':attribute muss eine erfolgreich hochgeladene Datei sein.',
		'filled'               => ':attribute muss ausgefüllt sein.',
		'gt'                   => [
			'array'   => ':attribute muss mehr als :value Elemente enthalten.',
			'file'    => ':attribute muss größer als :value kB sein.',
			'numeric' => ':attribute muss größer als :value sein.',
			'string'  => ':attribute muss mehr als :value Zeichen lang sein.',
		],
		'gte'                  => [
			'array'   => ':attribute muss mindestens :value Elemente enthalten.',
			'file'    => ':attribute muss mindestens :value kB groß sein.',
			'numeric' => ':attribute muss mindestens :value sein.',
			'string'  => ':attribute muss mindestens :value Zeichen enthalten.',
		],
		'hex_color'            => ':attribute muss eine gültige hexadezimale Farbe sein.',
		'image'                => ':attribute muss ein Bild sein.',
		'in'                   => ':attribute muss ein gültiger Wert sein. (Z.&nbsp;B. einer der vorgegebenen Werte)',
		'in_array'             => ':attribute muss in den Werten von :other enthalten sein.',
		'integer'              => ':attribute muss eine ganze Zahl sein.',
		'ip'                   => ':attribute muss eine gültige IP-Adresse sein.',
		'ipv4'                 => ':attribute muss eine gültige IPv4-Adresse sein.',
		'ipv6'                 => ':attribute muss eine gültige IPv6-Adresse sein.',
		'json'                 => ':attribute muss ein gültiger JSON-String sein.',
		'list'                 => ':attribute muss eine Liste sein.',
		'lowercase'            => ':attribute darf nur Kleinbuchstaben enthalten.',
		'lt'                   => [
			'array'   => ':attribute muss weniger als :value Elemente enhalten.',
			'file'    => ':attribute muss kleiner als :value kB sein.',
			'numeric' => ':attribute muss kleiner als :value sein.',
			'string'  => ':attribute muss weniger als :value Zeichen enthalten.',
		],
		'lte'                  => [
			'array'   => ':attribute darf höchstens :value Elemente enthalten.',
			'file'    => ':attribute darf höchstens :value kB groß sein.',
			'numeric' => ':attribute darf höchstens :value sein.',
			'string'  => ':attribute darf höchstens :value Zeichen enthalten.',
		],
		'mac_address'          => ':attribute muss eine gültige MAC-Adresse sein.',
		'max'                  => [
			'array'   => ':attribute darf nicht mehr als :max Elemente haben.',
			'file'    => ':attribute darf maximal :max Kilobytes groß sein.',
			'numeric' => ':attribute darf maximal :max sein.',
			'string'  => ':attribute darf maximal :max Zeichen haben.',
		],
		'max_digits'           => ':attribute darf höchstens :max Ziffern enthalten.',
		'mimes'                => ':attribute muss den Dateityp :values haben.',
		'mimetypes'            => ':attribute muss den Dateityp :values haben.',
		'min'                  => [
			'array'   => ':attribute muss mindestens :min Elemente haben.',
			'file'    => ':attribute muss mindestens :min Kilobytes groß sein.',
			'numeric' => ':attribute muss mindestens :min sein.',
			'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
		],
		'min_digits'           => ':attribute muss mindestens :min Ziffern enthalten.',
		'missing'              => ':attribute darf nicht vorhanden sein.',
		'missing_if'           => ':attribute darf nicht vorhanden sein, wenn :other dem Wert :value entspricht.',
		'missing_unless'       => ':attribute darf nicht vorhanden sein, solange :other nicht dem Wert :value entspricht.',
		'missing_with'         => ':attribute darf nicht vorhanden sein, wenn  :values angegeben ist.',
		'missing_with_all'     => ':attribute darf nicht vorhanden sein, wenn  :values angegeben sind.',
		'multiple_of'          => ':attribute  be a multiple of :value.',
		'not_in'               => ':attribute darf nicht in den vorgegebenen Werten enthalten sein.',
		'not_regex'            => ':attribute weist ein ungültiges Format auf.',
		'numeric'              => ':attribute muss eine Zahl sein.',
		'password'             => [
			'letters'       => ':attribute muss mindestens einen Buchstaben enthalten.',
			'mixed'         => ':attribute muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten.',
			'numbers'       => ':attribute muss mindestens eine Zahl enthalten.',
			'symbols'       => ':attribute muss mindestens ein Sonderzeichen enthalten.',
			'uncompromised' => ':attribute ist in einem Datenleck aufgetaucht. Bitte wähle ein neues :attribute.',
		],
		'present'              => ':attribute muss angegeben werden.',
		'present_if'           => ':attribute muss angegeben werden, wenn :other dem Wert :value entspricht.',
		'present_unless'       => ':attribute muss angegeben werden, solange :other nicht dem Wert :value entspricht.',
		'present_with'         => ':attribute muss angegeben werden, wenn :values angegeben ist.',
		'present_with_all'     => ':attribute muss angegeben werden, wenn :values angegeben sind.',
		'prohibited'           => ':attribute ist unzulässig.',
		'prohibited_if'        => ':attribute ist unzulässig, solange :other dem Wert :value entspricht.',
		'prohibited_unless'    => ':attribute ist unzulässig, solange :other nicht dem Wert :values entspricht.',
		'prohibits'            => ':attribute verhindert :other.',
		'regex'                => ':attribute entspricht keinem gültigen Format.',
		'regex2'               => ':attribute enthält ungültige Zeichen. Erlaubt sind: :chars',
		'required'             => ':attribute muss angegeben werden.',
		'required_array_keys'  => ':attribute muss Einträge für folgende Werte enthalten: :values.',
		'required_if'          => ':attribute muss angegeben werden.',
		'required_if_accepted' => ':attribute muss angegeben werden, solange :other angegeben ist.',
		'required_if_declined' => ':attribute muss angegeben werden, solange :other nicht angegeben ist.',
		'required_unless'      => ':attribute muss angegeben werden, solange :other nicht :values entspricht.',
		'required_with'        => ':attribute muss angegeben werden, wenn :values angegeben ist.',
		'required_with_all'    => ':attribute muss angegeben werden, wenn :values angegeben sind.',
		'required_without'     => ':attribute muss angegeben werden, wenn :values nicht ausgefüllt wurde.',
		'required_without_all' => ':attribute muss angegeben werden, wenn keines der Felder :values ausgefüllt wurde.',
		'same'                 => ':attribute und :other müssen übereinstimmen.',
		'size'                 => [
			'array'   => ':attribute muss genau :size Elemente haben.',
			'file'    => ':attribute muss :size Kilobyte groß sein.',
			'numeric' => ':attribute muss gleich :size sein.',
			'string'  => ':attribute muss :size Zeichen lang sein.',
		],
		'starts_with'          => ':attribute muss mit einem der folgenden Werte beginnen: :values.',
		'string'               => ':attribute muss eine Zeichenkette sein.',
		'timezone'             => ':attribute muss eine gültige Zeitzone sein.',
		'unique'               => ':attribute darf nicht bereits existieren.',
		'uploaded'             => ':attribute muss eine erfolgreich hochgeladene Datei sein.',
		'uppercase'            => ':attribute darf nur Großbuchstaben enthalten.',
		'url'                  => ':attribute muss eine gültige URL sein.',
		'ulid'                 => ':attribute muss eine gültige ULID sein.',
		'uuid'                 => ':attribute muss eine gültige UUID sein.',

		/*
		|--------------------------------------------------------------------------
		| Custom Validation Language Lines
		|--------------------------------------------------------------------------
		|
		| Here you may specify custom validation messages for attributes using the
		| convention "attribute.rule" to name the lines. This makes it quick to
		| specify a specific custom language line for a given attribute rule.
		|
		*/

		'custom' => [
			'password' => [
				'regex' => 'Das Passwort entspricht nicht den Vorgaben.'
			],
		],

		/*
		|--------------------------------------------------------------------------
		| Custom Validation Attributes
		|--------------------------------------------------------------------------
		|
		| The following language lines are used to swap our attribute placeholder
		| with something more reader friendly such as "E-Mail Address" instead
		| of "email". This simply helps us make our message more expressive.
		|
		*/

		'attributes' => [
			'available'               => 'Verfügbar',
			'currentPassword'         => 'Aktuelles Passwort',
			'deposit'                 => 'Kaution',
			'description'             => 'Beschreibung',
			'email'                   => 'E-Mail-Adresse',
			'enabled'                 => 'Benutzer aktiv',
			'forename'                => 'Vorname',
			'keepStock'               => 'Bestand verwalten',
			'name'                    => 'Name',
			'newItem'                 => 'Neuer Artikel',
			'newPassword'             => 'Neues Passwort',
			'newPasswordConfirmation' => 'Neues Passwort (Wiederholung)',
			'password'                => 'Passwort',
			'passwordConfirmation'    => 'Passwortbestätigung',
			'price'                   => 'Preis',
			'rememberme'              => 'Eingeloggt bleiben',
			'role'                    => 'Rolle',
			'stock'                   => 'Bestand',
			'surname'                 => 'Nachname',
			'username'                => 'Benutzername',
			'visible'                 => 'Sichtbar',
			'itemGroup'               => 'Artikelgruppe',
		]
	];
