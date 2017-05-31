<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa user o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações
// com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'investim');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** Nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Charset do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'eCfc[xP;W/3dR7AysVnF[V3QBo-i9&hiFL4$Fu.APS=ui8pK)UH5] C11W]{TK+<');
define('SECURE_AUTH_KEY',  'yv&~oxpB9KdmRwC(y%8dQT{DQ42Z%r%Z~nYfnfhcf}%`KiMy~HN,:(-jcr{4.Vwx');
define('LOGGED_IN_KEY',    '&1,mCTM9x/~q;}T/fmg^nal=xg(?mu^zUQGC9{40N:V0|Cn02o$D> ~AM*UOyKQ$');
define('NONCE_KEY',        't|M`hVPG;IStao+}SvY/+N_Q,0k--l<+r@z.VD?bEv$16nq{y5mn.xOqei;ZKoH)');
define('AUTH_SALT',        'S^_o)d)p;:9CMR>CZ%GK/o{A;$!Q!e}7ICXL:ng~kNj7Y;?cyVyO,jCq9G`#Zf1[');
define('SECURE_AUTH_SALT', '_pT&GVQhDq`qC~q1X5ObyMXjm2&kfX0>bN!R!I&*F$KJT?K3By$yYob;| 80:7P9');
define('LOGGED_IN_SALT',   'm#//#kO70+#[qKa7%bUn<|mQ/_4/hwsn%P#=xoSBI~.p%yf7:k8s,r*n8u&Ci0x.');
define('NONCE_SALT',       'Hi(ZF{vzuU1MLE>XiEq/:s`H8[yKh?aC$!cbtuQ5b%$SU%N.m5bQuiI*PdN%m]8S');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * para cada um um único prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'investimwp_';

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
