<?php

return [
    'Base DN' => 'Base DN',
    'Defines the filter to apply, when login is attempted. %s replaces the username in the login action. Example: &quot;(sAMAccountName=%s)&quot; or &quot;(uid=%s)&quot;' => 'Define o filtro a ser aplicado, quando o login é feito. %s substitui o nome de usuário no processo de login. Exemplo: "(sAMAccountName=%s)" ou "(uid=%s)"',
    'E-Mail Address Attribute' => 'Atributo de endereço de e-mail',
    'Enable LDAP Support' => 'Habilitar suporte LDAP',
    'Encryption' => 'Criptografia',
    'Fetch/Update Users Automatically' => 'Busque/Atualize usuários automaticamente',
    'Hostname' => 'Servidor',
    'ID Attribute' => 'Atributo ID',
    'Ignored LDAP entries' => 'Entradas LDAP ignoradas',
    'LDAP' => 'LDAP',
    'LDAP Attribute for E-Mail Address. Default: &quot;mail&quot;' => 'Atributo LDAP para endereço de email. Padrão: "mail"',
    'LDAP Attribute for Username. Example: &quot;uid&quot; or &quot;sAMAccountName&quot;' => 'Atributo LDAP para nome de usuário. Exemplo: "uid" or "sAMAccountName"',
    'Limit access to users meeting this criteria. Example: &quot;(objectClass=posixAccount)&quot; or &quot;(&(objectClass=person)(memberOf=CN=Workers,CN=Users,DC=myDomain,DC=com))&quot;' => 'Limite o acesso aos usuários que atendem a esse critério. Exemplo: "(objectClass=posixAccount)" or "(&amp;(objectClass=person)(memberOf=CN=Workers,CN=Users,DC=myDomain,DC=com))"',
    'Login Filter' => 'Filtro de login',
    'Not changeable LDAP attribute to unambiguously identify the user in the directory. If empty the user will be determined automatically by e-mail address or username. Examples: objectguid (ActiveDirectory) or uidNumber (OpenLDAP)' => 'Atributo LDAP não alterável para desambiguação da identificação do usuário no diretório. Se vazio, o usuário será determinado automaticamente pelo endereço de email ou nome de usuário. Por exemplo: objectguid (ActiveDirectory) ou uidNumber (OpenLDAP)',
    'One DN per line which should not be imported automatically.' => 'Um DN por linha que não deve ser importado automaticamente.',
    'Password' => 'Senha',
    'Port' => 'Porta',
    'Specify your LDAP-backend used to fetch user accounts.' => 'Especifique seu usuário LDAP para buscar as contas de usuários.',
    'Status: Error! (Message: {message})' => 'Status: Erro! (Mensagem: {message})',
    'Status: OK! ({userCount} Users)' => 'Status: OK! ({userCount} Usuários)',
    'Status: Warning! (No users found using the ldap user filter!)' => 'Status: Atenção! (Nenhum usuário encontrado usando o filtro de usuário LDAP!)',
    'The default base DN used for searching for accounts.' => 'A base padrão DN utilizado para a busca de contas.',
    'The default credentials password (used only with username above).' => 'A senha padrão (usada apenas com nome de usuário acima).',
    'The default credentials username. Some servers require that this be in DN form. This must be given in DN form if the LDAP server requires a DN to bind and binding should be possible with simple usernames.' => 'O usuário padrão. Alguns servidores exigem que este seja em forma DN. Isso deve ser dada de forma DN se o servidor LDAP requer uma DN para vincular e deve ser possível com nomes de usuários simples.',
    'User Filter' => 'Filtro de Usuário',
    'Username' => 'Nome de usuário',
    'Username Attribute' => 'Atributo do nome do usuário',
];
