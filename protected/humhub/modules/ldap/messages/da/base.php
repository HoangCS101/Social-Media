<?php

return [
    'Base DN' => 'Base DN',
    'Defines the filter to apply, when login is attempted. %s replaces the username in the login action. Example: &quot;(sAMAccountName=%s)&quot; or &quot;(uid=%s)&quot;' => 'Definerer filteret for at anvende, når der er forsøgt på at logge ind. %s udskrifter brugernavnet ved login. Eksempel: &quot;(sAMAccountName=%s)&quot; or &quot;(uid=%s)&quot;',
    'E-Mail Address Attribute' => 'E-Mail Adresse Attribut',
    'Enable LDAP Support' => 'Aktiver LDAP Support',
    'Encryption' => 'Kryptering',
    'Fetch/Update Users Automatically' => 'Hent/Opdater Brugere Automatisk',
    'Hostname' => 'Vært',
    'LDAP' => 'LDAP',
    'Login Filter' => 'Login Filter',
    'Password' => 'Adgangskode',
    'Port' => 'Port',
    'Status: Error! (Message: {message})' => 'Status: Fejl! (Besked: {message})',
    'Status: OK! ({userCount} Users)' => 'Status: OK! ({userCount} Brugere)',
    'The default base DN used for searching for accounts.' => 'Standard basen som DN bruger for at søge efter kontier',
    'The default credentials password (used only with username above).' => 'Standard legitimationsoplysning i adgangskode (bruges kun med brugernavn ovenfor).',
    'The default credentials username. Some servers require that this be in DN form. This must be given in DN form if the LDAP server requires a DN to bind and binding should be possible with simple usernames.' => 'Standardindstillingerne legitimationsoplysninger brugernavn . Nogle servere kræver , at dette skal være i DN form. Dette skal angives i DN formularen, hvis LDAP-serveren kræver en DN at forbinde til og forbindendelse bør være muligt ved simple brugernavne .',
    'User Filter' => 'Bruger Filer',
    'Username' => 'Brugernavn',
    'Username Attribute' => 'Brugernavn Attribut',
    'ID Attribute' => '',
    'Ignored LDAP entries' => '',
    'LDAP Attribute for E-Mail Address. Default: &quot;mail&quot;' => '',
    'LDAP Attribute for Username. Example: &quot;uid&quot; or &quot;sAMAccountName&quot;' => '',
    'Limit access to users meeting this criteria. Example: &quot;(objectClass=posixAccount)&quot; or &quot;(&(objectClass=person)(memberOf=CN=Workers,CN=Users,DC=myDomain,DC=com))&quot;' => '',
    'Not changeable LDAP attribute to unambiguously identify the user in the directory. If empty the user will be determined automatically by e-mail address or username. Examples: objectguid (ActiveDirectory) or uidNumber (OpenLDAP)' => '',
    'One DN per line which should not be imported automatically.' => '',
    'Specify your LDAP-backend used to fetch user accounts.' => '',
    'Status: Warning! (No users found using the ldap user filter!)' => '',
];
