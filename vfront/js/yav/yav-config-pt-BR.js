/***********************************************************************
 * YAV - Yet Another Validator  v1.3.0                                 *
 * Copyright (C) 2005-2006                                             *
 * Author: Federico Crivellaro <f.crivellaro@gmail.com>                *
 * WWW: http://yav.sourceforge.net                                     *
 ***********************************************************************/

// CHANGE THESE VARIABLES FOR YOUR OWN SETUP

// if you want yav to highligh fields with errors
inputhighlight = true;
// classname you want for the error highlighting
inputclasserror = 'inputError';
// classname you want for your fields without highlighting
inputclassnormal = 'inputNormal';
// classname you want for the inner html highlighting
innererror = 'innerError';
// div name where errors will appear (or where jsVar variable is dinamically defined)
errorsdiv = 'errorsDiv';
// if you want yav to alert you for javascript errors (only for developers)
debugmode = false;
// if you want yav to trim the strings
trimenabled = true;

// change these to set your own decimal separator and your date format
DECIMAL_SEP ='.';
THOUSAND_SEP = ',';
DATE_FORMAT = 'MM-dd-yyyy';

// change these strings for your own translation (do not change {n} values!)
HEADER_MSG = 'Dados inv�lidos:';
FOOTER_MSG = 'Por favor, tente novamente.';
DEFAULT_MSG = 'Dados inv�lidos';
REQUIRED_MSG = 'Insira {1}.';
ALPHABETIC_MSG = '{1} � inv�lido. Characters allowed: A-Za-z';
ALPHANUMERIC_MSG = '{1} n�o � v�lido. Caracteres permitidos: A-Za-z0-9';
ALNUMHYPHEN_MSG = '{1} n�o � v�lido. Caracteres permitidos: A-Za-z0-9\-_';
ALNUMHYPHENAT_MSG = '{1} n�o � v�lido. Caracteres permitidos: A-Za-z0-9\-_@';
ALPHASPACE_MSG = '{1} n�o � v�lido. Caracteres permitidos: A-Za-z0-9\-_space';
MINLENGTH_MSG = '{1} deve ter pelo menos {2} caracteres.';
MAXLENGTH_MSG = '{1} n�o pode ter mais que {2} caracteres.';
NUMRANGE_MSG = '{1} deve ser um n�mero no intervalo {2}.';
DATE_MSG = '{1} n�o � uma data v�lida, no formato ' + DATE_FORMAT + '.';
NUMERIC_MSG = '{1} deve ser um n�mero.';
INTEGER_MSG = '{1} deve ser um n�mero inteiro.';
DOUBLE_MSG = '{1} deve ser um n�mero decimal.';
REGEXP_MSG = '{1} n�o � v�lido. Formato permitido: {2}.';
EQUAL_MSG = '{1} deve ser igual a {2}.';
NOTEQUAL_MSG = '{1} n�o pode ser igual a {2}.';
DATE_LT_MSG = '{1} deve ser menor que {2}.';
DATE_LE_MSG = '{1} deve ser menor ou igual a {2}.';
EMAIL_MSG = '{1} deve ser um e-mail v�lido.';
EMPTY_MSG = '{1} deve ser vacio.';
