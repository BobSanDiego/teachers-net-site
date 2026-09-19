<?php

defined('ABSPATH') || exit;

/**
 * Canonical, deliberately low-resolution member location vocabulary.
 *
 * This policy owns Screen 5 validation only. It does not resolve addresses,
 * infer member geography, or provide a location for targeting.
 */
final class TNet_Identity_Location_Policy {
  public static function us_regions() {
    return [
      'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
      'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
      'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii',
      'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
      'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
      'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
      'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
      'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
      'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
      'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
      'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
      'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
      'WI' => 'Wisconsin', 'WY' => 'Wyoming',
    ];
  }

  /** ISO 3166-1 alpha-2 choices outside the United States. */
  public static function country_codes() {
    return explode(',', 'AW,AF,AO,AI,AX,AL,AD,AE,AR,AM,AS,AQ,TF,AG,AU,AT,AZ,BI,BE,BJ,BQ,BF,BD,BG,BH,BS,BA,BL,BY,BZ,BM,BO,BR,BB,BN,BT,BV,BW,CF,CA,CC,CH,CL,CN,CI,CM,CD,CG,CK,CO,KM,CV,CR,CU,CW,CX,KY,CY,CZ,DE,DJ,DM,DK,DO,DZ,EC,EG,ER,EH,ES,EE,ET,FI,FJ,FK,FR,FO,FM,GA,GB,GE,GG,GH,GI,GN,GP,GM,GW,GQ,GR,GD,GL,GT,GF,GU,GY,HK,HM,HN,HR,HT,HU,ID,IM,IN,IO,IE,IR,IQ,IS,IL,IT,JM,JE,JO,JP,KZ,KE,KG,KH,KI,KN,KR,KW,LA,LB,LR,LY,LC,LI,LK,LS,LT,LU,LV,MO,MF,MA,MC,MD,MG,MV,MX,MH,MK,ML,MT,MM,ME,MN,MP,MZ,MR,MS,MQ,MU,MW,MY,YT,NA,NC,NE,NF,NG,NI,NU,NL,NO,NP,NR,NZ,OM,PK,PA,PN,PE,PH,PW,PG,PL,PR,KP,PT,PY,PS,PF,QA,RE,RO,RU,RW,SA,SD,SN,SG,GS,SH,SJ,SB,SL,SV,SM,SO,PM,RS,SS,ST,SR,SK,SI,SE,SZ,SX,SC,SY,TC,TD,TG,TH,TJ,TK,TM,TL,TO,TT,TN,TR,TV,TW,TZ,UG,UA,UM,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,ZA,ZM,ZW');
  }

  public static function normalize_region($value) {
    $code = strtoupper(trim((string) $value));
    return array_key_exists($code, self::us_regions()) ? $code : '';
  }

  public static function normalize_country($value) {
    $code = strtoupper(trim((string) $value));
    return in_array($code, self::country_codes(), true) ? $code : '';
  }
}
