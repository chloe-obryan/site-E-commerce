<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'shop' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'jC//ne-]ke{a=}hg/b*cNefN@r]~p)dy(oY>}#k=M4@W*Rkni4K{I/@L&?szq?ks' );
define( 'SECURE_AUTH_KEY',  '`@MBdlYvbR,ntAFOk!s8~+bQOl_OArbuQFkub-VF^@}?0@yLW!TZ.]+#j^Fs#~k/' );
define( 'LOGGED_IN_KEY',    'M`nQ#C6.M`3L%MJ*h<G6BajH[.L2-&`pR=&hklKR?PjBN!z{X6VCF6@K%ELcE$RR' );
define( 'NONCE_KEY',        'TwPA|[O_)IV7}#<P4M+&SB{J3Wqk:GtwJtJg9tXW^}a-NnaD-1DW;k~RC1=8|IWD' );
define( 'AUTH_SALT',        '*j~hi$[|~m0P`P({w<<e0H&WFp*N/:|^mB{8#[;1IO%J3&6sG8tz#xaV7wHtRuKm' );
define( 'SECURE_AUTH_SALT', '9%%x1Jr]Fl+gn~2D444z1%f]axU]!}|@au6!t/WB+/C=~;TW#3nG4)kMH8mzNu=x' );
define( 'LOGGED_IN_SALT',   'N={,+w.[{fa)ViG%`o|,X_oF)j+De6z(RRaQ.ED~;)t&?gsvz3$k!2?{zx4#8Z[l' );
define( 'NONCE_SALT',       'oM=tSObz9.|RWZ5S-q8TICA+.P%sqx_OSHKQ !v42`^H9_b3vbx>`>UN0UqeiAAM' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
