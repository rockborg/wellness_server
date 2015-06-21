<?php
########################################################################
#
#	 FILE DI CONFIGURAZIONE VFRONT
#
#
#	 This file is part of VFront.
#
#    VFront is free software; you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation; either version 2 of the License, or
#    any later version.
#
#    VFront is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
########################################################################

/**
 * @desc VFront Configuration file
 * @package VFront
 * @subpackage Config
 * @author M.Marcello Verona
 * @copyright 2007-2010 M.Marcello Verona
 * @version 0.96 $Id: create_conf.php 1076 2014-06-13 13:03:44Z marciuz $
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License 
 */
 
 
/*  DB  CONNECTION */


// Connessione a MYSQL 5.x: commentare il blocco nel caso si utilizzi altro DB (Postgres)
$db1['dbtype']="mysql";
$db1['host']="localhost";
$db1['port']="3306";
$db1['user']="root";
$db1['passw']="root";
$db1['dbname']="wellness";
$db1['frontend']="wellness_vfront";
$db1['sep']=".";

define('VFRONT_DBTYPE', $db1['dbtype']);
    


// PARAMETRI PER LA MODALITA' DI AUTENTICAZIONE ESTERNA  --------------------------------------------------------------------


// Questo parametro permette di effettuare l'autenticazion mediante uno strumento esterno (database, ldap, eccetera)
// Qualora si volesse effettuare l'autenticazione direttamente dal database di VFront si imposti la variabile = '' oppure null

$conf_auth['tipo_external_auth']= ''; // 'db' | 'db_ext' | 'ldap' | 'soap' | null

//--------------   Fine autenticazione esterna  --------------  //


// SEZIONE SMTP E MAIL  
// qualora si voglia utilizzare un SMTP personalizzato per la gestione delle email 

$conf_mail['SMTP_AUTH']=false;
$conf_mail['SMTP']="";
$conf_mail['SMTP_AUTH_USER']="";
$conf_mail['SMTP_AUTH_PASSW']="";
$conf_mail['MAIL_SENDER']="omotola@ualberta.ca";
$conf_mail['MAIL_SENDER_NAME']="VFront admin";

/**
 * mail amministratore di sistema
 */
define('_SYS_ADMIN_MAIL','omotola@ualberta.ca');

/**
 * mail dello sviluppatore (per le email di debug
 */
define('_DEV_MAIL','omotola@ualberta.ca');




/* SEZIONE DEBUG */

/**
 * errori a video | errori in email
 * In ambiente di produzione si consiglia di 
 * impostare la variabile su FALSE: in caso di errore verra' spedita una email all'amministratore
 * ed allo sviluppatore. L'utente vede una schermata dove si comunica che e' stato generato un errore.
 * In caso la variabile sia TRUE gli errori verranno invece mostrati a video
 */
$DEBUG_SQL=false;

/**
 * apri un popup via javascript che mostra le query SQL  - default: FALSE
 */
$DEBUG_SQL_SHOW_QUERY=false;

/**
 * scrivi le chiamate SQL in un file (di default ./rpc.debug.txt)  - default: FALSE
 */
$RPC_DEBUG=false;




/* SEZIONE LOG */

/**
 * scrive un log delle chiamate SQL di inserimento, modifica e cancellazione - default: TRUE
 */
$RPC_LOG=true;


/*  SEZIONE LANGUAGE AND ENCODING  */

/**
 * Language : Valori possibili:  en_US, fr_FR, it_IT, de_DE...
 */
define('FRONT_LANG','en_US');



/**
 * Encoding
 */
define('FRONT_ENCODING','UTF-8');




/*  SEZIONE DATE */

/**
 * Date format: (iso,eng,ita)
 */
define('FRONT_DATE_FORMAT','iso');




/*  SEZIONE PATH  */

/**
 * path reale
 */
define('FRONT_ROOT','/Users/tola/Documents/Aptana Studio 3 Workspace/wellness_server/vfront');

/**
 * path reale
 */
define('FRONT_REALPATH','/Users/tola/Documents/Aptana Studio 3 Workspace/wellness_server/vfront');



/**
 * Path della document root
 */
define('FRONT_DOCROOT','http://localhost/wellness_server/vfront');

/**
 * Path mysqldump (per l'esportazione di MySQL) - Default: mysqldump
 */
define('_PATH_MYSQLDUMP','mysqldump');

/**
 * path pg_dump (per l'esportazione di Postgres) - Default: pg_dump
 */
define('_PATH_PG_DUMP','pg_dump');

/**
 * path per il filesystem allegati
 */
define('_PATH_ATTACHMENT',FRONT_REALPATH.'/files/data');

/**
 * path di tmp per il filesystem allegati
 */
define('_PATH_ATTACHMENT_TMP',FRONT_REALPATH.'/files/tmp');

/**
 * path per il filesystem documenti utili
 */
define('_PATH_HELPDOCS',FRONT_REALPATH.'/files/docs');

/**
 * path per il filesystem documenti utili admin
 */
define('_PATH_HELPDOCS2',FRONT_REALPATH.'/files/docsadmin');

/**
 * path di tmp accessibile via web
 */
define('_PATH_TMP',FRONT_REALPATH.'/files/tmp');

/**
 * path di tmp accessibile via web
 */
define('_PATH_TMP_HTTP',FRONT_DOCROOT.'/files/tmp');

/**
 * path per i fogli di stile XSL allegati
 */
define('_PATH_XSL',FRONT_REALPATH.'/files/xsl_custom');

/**
 * path web per i fogli di stile XSL allegati
 */
define('_PATH_WEB_XSL',FRONT_DOCROOT.'/files/xsl_custom');

/**
 * path per error log
 */
define('FRONT_ERROR_LOG',FRONT_REALPATH.'/files/db/error_log.txt');





/*  SEZIONE FOP  */
/* Utilizza l'applicazione Apache FOP http://xmlgraphics.apache.org/fop/ 
per generare la versione PDF dei file XML */

/**
 * Imposta se Vfront puo' utilizzare l'applicazione FOP 
 */
define('_FOP_ENABLED',false);

/**
 * Imposta se Vfront puo' utilizzare l'applicazione FOP 
 */
define('_PATH_FOP','');






/*  SEZIONE ALLEGATI E LINK  */

/**
 * definizione della tabella allegato
 */
define('_TABELLA_ALLEGATO',"{$db1['frontend']}{$db1['sep']}allegato");

/**
 * definizione della tabella link
 */
define('_TABELLA_LINK',"{$db1['frontend']}{$db1['sep']}link");





/*  SEZIONE MISC  */


/**
 * massimo tempo di editing di un record per considerarlo bloccato (in secondi)
 */
define('_MAX_TEMPO_EDIT',240);

/**
 * passphrase per le codifiche base64
 */
define('_BASE64_PASSFRASE',"dhikmnqx");

/**
 * Nome progetto
 */
define('_NOME_PROJ','VFront');





/* SEZIONE SQLITE */

define('USE_REG_SQLITE',false);
define('VERSION_REG_SQLITE',3);

// SQLite Reg
$db1['filename_reg']="/Users/tola/Documents/Aptana Studio 3 Workspace/wellness_server/vfront/files/db/vfront.sqlite"; // path of sqlite database





?>