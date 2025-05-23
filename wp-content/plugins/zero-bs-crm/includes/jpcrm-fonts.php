<?php 
/*!
 * Jetpack CRM
 * https://jetpackcrm.com
 *
 * Logic concerned with installing and using different fonts, primarily in the creation of PDF files
 *
 */

// Require DOMPDF
global $zbs; $zbs->libLoad('dompdf');
use FontLib\Font;

defined( 'ZEROBSCRM_PATH' ) || exit( 0 );



/*
* Class encapsulating logic concerned with installing and using different fonts
*/
class JPCRM_Fonts {

	public function __construct( ) {

	}


	/*
	* Returns a list of fonts available via our CDN:
	*
	* @param: $cleaned_alphabetical bool - if true return the list with 'Noto' moved to back of string and re-ordered to be alphabetic
	* ... e.g. 'Noto Kufi Arabic' => 'Kufi Arabic (Noto)'
	*/
	public function list_all_available( $cleaned_alphabetical=false ){

		// updated 17/04/24: Noto Sans, and added CJK fonts (JP, HK, TC, KR and SC)
		$font_json =
		'{"Boku2.zip":"Boku2","NotoKufiArabic.zip":"Noto Kufi Arabic","NotoLoopedLao.zip":"Noto Looped Lao","NotoLoopedLaoUI.zip":"Noto Looped Lao UI","NotoLoopedThai.zip":"Noto Looped Thai","NotoLoopedThaiUI.zip":"Noto Looped Thai UI","NotoMusic.zip":"Noto Music","NotoNaskhArabic.zip":"Noto Naskh Arabic","NotoNaskhArabicUI.zip":"Noto Naskh Arabic UI","NotoNastaliqUrdu.zip":"Noto Nastaliq Urdu","NotoRashiHebrew.zip":"Noto Rashi Hebrew","NotoSansAdlam.zip":"Noto Sans Adlam","NotoSansAdlamUnjoined.zip":"Noto Sans Adlam Unjoined","NotoSansAnatolianHieroglyphs.zip":"Noto Sans Anatolian Hieroglyphs","NotoSansArabic.zip":"Noto Sans Arabic","NotoSansArabicUI.zip":"Noto Sans Arabic UI","NotoSansArmenian.zip":"Noto Sans Armenian","NotoSansAvestan.zip":"Noto Sans Avestan","NotoSansBalinese.zip":"Noto Sans Balinese","NotoSansBamum.zip":"Noto Sans Bamum","NotoSansBassaVah.zip":"Noto Sans Bassa Vah","NotoSansBatak.zip":"Noto Sans Batak","NotoSansBengali.zip":"Noto Sans Bengali","NotoSansBengaliUI.zip":"Noto Sans Bengali UI","NotoSansBhaiksuki.zip":"Noto Sans Bhaiksuki","NotoSansBrahmi.zip":"Noto Sans Brahmi","NotoSansBuginese.zip":"Noto Sans Buginese","NotoSansBuhid.zip":"Noto Sans Buhid","NotoSansCanadianAboriginal.zip":"Noto Sans Canadian Aboriginal","NotoSansCarian.zip":"Noto Sans Carian","NotoSansCaucasianAlbanian.zip":"Noto Sans Caucasian Albanian","NotoSansChakma.zip":"Noto Sans Chakma","NotoSansCham.zip":"Noto Sans Cham","NotoSansCherokee.zip":"Noto Sans Cherokee","NotoSansCoptic.zip":"Noto Sans Coptic","NotoSansCuneiform.zip":"Noto Sans Cuneiform","NotoSansCypriot.zip":"Noto Sans Cypriot","NotoSansDeseret.zip":"Noto Sans Deseret","NotoSansDevanagari.zip":"Noto Sans Devanagari","NotoSansDevanagariUI.zip":"Noto Sans Devanagari UI","NotoSansDisplay.zip":"Noto Sans Display","NotoSansDuployan.zip":"Noto Sans Duployan","NotoSansEgyptianHieroglyphs.zip":"Noto Sans Egyptian Hieroglyphs","NotoSansElbasan.zip":"Noto Sans Elbasan","NotoSansElymaic.zip":"Noto Sans Elymaic","NotoSansEthiopic.zip":"Noto Sans Ethiopic","NotoSansGeorgian.zip":"Noto Sans Georgian","NotoSansGlagolitic.zip":"Noto Sans Glagolitic","NotoSansGothic.zip":"Noto Sans Gothic","NotoSansGrantha.zip":"Noto Sans Grantha","NotoSansGujarati.zip":"Noto Sans Gujarati","NotoSansGujaratiUI.zip":"Noto Sans Gujarati UI","NotoSansGunjalaGondi.zip":"Noto Sans Gunjala Gondi","NotoSansGurmukhi.zip":"Noto Sans Gurmukhi","NotoSansGurmukhiUI.zip":"Noto Sans Gurmukhi UI","NotoSansHanifiRohingya.zip":"Noto Sans Hanifi Rohingya","NotoSansHanunoo.zip":"Noto Sans Hanunoo","NotoSansHatran.zip":"Noto Sans Hatran","NotoSansHebrew.zip":"Noto Sans Hebrew","NotoSansHongKong.zip":"Noto Sans Hong Kong","NotoSansImperialAramaic.zip":"Noto Sans Imperial Aramaic","NotoSansIndicSiyaqNumbers.zip":"Noto Sans Indic Siyaq Numbers","NotoSansInscriptionalPahlavi.zip":"Noto Sans Inscriptional Pahlavi","NotoSansInscriptionalParthian.zip":"Noto Sans Inscriptional Parthian","NotoSansJapanese.zip":"Noto Sans Japanese","NotoSansJavanese.zip":"Noto Sans Javanese","NotoSansKaithi.zip":"Noto Sans Kaithi","NotoSansKannada.zip":"Noto Sans Kannada","NotoSansKannadaUI.zip":"Noto Sans Kannada UI","NotoSansKayahLi.zip":"Noto Sans Kayah Li","NotoSansKharoshthi.zip":"Noto Sans Kharoshthi","NotoSansKhmer.zip":"Noto Sans Khmer","NotoSansKhmerUI.zip":"Noto Sans Khmer UI","NotoSansKhojki.zip":"Noto Sans Khojki","NotoSansKhudawadi.zip":"Noto Sans Khudawadi","NotoSansKorean.zip":"Noto Sans Korean","NotoSansLao.zip":"Noto Sans Lao","NotoSansLaoUI.zip":"Noto Sans Lao UI","NotoSansLepcha.zip":"Noto Sans Lepcha","NotoSansLimbu.zip":"Noto Sans Limbu","NotoSansLinearA.zip":"Noto Sans Linear A","NotoSansLinearB.zip":"Noto Sans Linear B","NotoSansLisu.zip":"Noto Sans Lisu","NotoSansLycian.zip":"Noto Sans Lycian","NotoSansLydian.zip":"Noto Sans Lydian","NotoSansMahajani.zip":"Noto Sans Mahajani","NotoSansMalayalam.zip":"Noto Sans Malayalam","NotoSansMalayalamUI.zip":"Noto Sans Malayalam UI","NotoSansMandaic.zip":"Noto Sans Mandaic","NotoSansManichaean.zip":"Noto Sans Manichaean","NotoSansMarchen.zip":"Noto Sans Marchen","NotoSansMasaramGondi.zip":"Noto Sans Masaram Gondi","NotoSansMath.zip":"Noto Sans Math","NotoSansMayanNumerals.zip":"Noto Sans Mayan Numerals","NotoSansMedefaidrin.zip":"Noto Sans Medefaidrin","NotoSansMeeteiMayek.zip":"Noto Sans Meetei Mayek","NotoSansMendeKikakui.zip":"Noto Sans Mende Kikakui","NotoSansMeroitic.zip":"Noto Sans Meroitic","NotoSansMiao.zip":"Noto Sans Miao","NotoSansModi.zip":"Noto Sans Modi","NotoSansMongolian.zip":"Noto Sans Mongolian","NotoSansMono.zip":"Noto Sans Mono","NotoSansMro.zip":"Noto Sans Mro","NotoSansMultani.zip":"Noto Sans Multani","NotoSansMyanmar.zip":"Noto Sans Myanmar","NotoSansMyanmarUI.zip":"Noto Sans Myanmar UI","NotoSansNKo.zip":"Noto Sans N Ko","NotoSansNabataean.zip":"Noto Sans Nabataean","NotoSansNewTaiLue.zip":"Noto Sans New Tai Lue","NotoSansNewa.zip":"Noto Sans Newa","NotoSansNushu.zip":"Noto Sans Nushu","NotoSansOgham.zip":"Noto Sans Ogham","NotoSansOlChiki.zip":"Noto Sans Ol Chiki","NotoSansOldHungarian.zip":"Noto Sans Old Hungarian","NotoSansOldItalic.zip":"Noto Sans Old Italic","NotoSansOldNorthArabian.zip":"Noto Sans Old North Arabian","NotoSansOldPermic.zip":"Noto Sans Old Permic","NotoSansOldPersian.zip":"Noto Sans Old Persian","NotoSansOldSogdian.zip":"Noto Sans Old Sogdian","NotoSansOldSouthArabian.zip":"Noto Sans Old South Arabian","NotoSansOldTurkic.zip":"Noto Sans Old Turkic","NotoSansOriya.zip":"Noto Sans Oriya","NotoSansOriyaUI.zip":"Noto Sans Oriya UI","NotoSansOsage.zip":"Noto Sans Osage","NotoSansOsmanya.zip":"Noto Sans Osmanya","NotoSansPahawhHmong.zip":"Noto Sans Pahawh Hmong","NotoSansPalmyrene.zip":"Noto Sans Palmyrene","NotoSansPauCinHau.zip":"Noto Sans Pau Cin Hau","NotoSansPhagsPa.zip":"Noto Sans Phags Pa","NotoSansPhoenician.zip":"Noto Sans Phoenician","NotoSansPsalterPahlavi.zip":"Noto Sans Psalter Pahlavi","NotoSansRejang.zip":"Noto Sans Rejang","NotoSansRunic.zip":"Noto Sans Runic","NotoSansSamaritan.zip":"Noto Sans Samaritan","NotoSansSaurashtra.zip":"Noto Sans Saurashtra","NotoSansSimplifiedChinese.zip":"Noto Sans Simplified Chinese","NotoSansSharada.zip":"Noto Sans Sharada","NotoSansShavian.zip":"Noto Sans Shavian","NotoSansSiddham.zip":"Noto Sans Siddham","NotoSansSignWriting.zip":"Noto Sans Sign Writing","NotoSansSinhala.zip":"Noto Sans Sinhala","NotoSansSinhalaUI.zip":"Noto Sans Sinhala UI","NotoSansSogdian.zip":"Noto Sans Sogdian","NotoSansSoraSompeng.zip":"Noto Sans Sora Sompeng","NotoSansSoyombo.zip":"Noto Sans Soyombo","NotoSansSundanese.zip":"Noto Sans Sundanese","NotoSansSylotiNagri.zip":"Noto Sans Syloti Nagri","NotoSansSymbols.zip":"Noto Sans Symbols","NotoSansSymbols2.zip":"Noto Sans Symbols2","NotoSansSyriac.zip":"Noto Sans Syriac","NotoSansTagalog.zip":"Noto Sans Tagalog","NotoSansTagbanwa.zip":"Noto Sans Tagbanwa","NotoSansTaiLe.zip":"Noto Sans Tai Le","NotoSansTaiTham.zip":"Noto Sans Tai Tham","NotoSansTaiViet.zip":"Noto Sans Tai Viet","NotoSansTakri.zip":"Noto Sans Takri","NotoSansTamil.zip":"Noto Sans Tamil","NotoSansTamilSupplement.zip":"Noto Sans Tamil Supplement","NotoSansTamilUI.zip":"Noto Sans Tamil UI","NotoSansTaiwan.zip":"Noto Sans Taiwan","NotoSansTelugu.zip":"Noto Sans Telugu","NotoSansTeluguUI.zip":"Noto Sans Telugu UI","NotoSansThaana.zip":"Noto Sans Thaana","NotoSansThai.zip":"Noto Sans Thai","NotoSansThaiUI.zip":"Noto Sans Thai UI","NotoSansTifinagh.zip":"Noto Sans Tifinagh","NotoSansTirhuta.zip":"Noto Sans Tirhuta","NotoSansUgaritic.zip":"Noto Sans Ugaritic","NotoSansVai.zip":"Noto Sans Vai","NotoSansWancho.zip":"Noto Sans Wancho","NotoSansWarangCiti.zip":"Noto Sans Warang Citi","NotoSansYi.zip":"Noto Sans Yi","NotoSansZanabazarSquare.zip":"Noto Sans Zanabazar Square","NotoSerifAhom.zip":"Noto Serif Ahom","NotoSerifArmenian.zip":"Noto Serif Armenian","NotoSerifBalinese.zip":"Noto Serif Balinese","NotoSerifBengali.zip":"Noto Serif Bengali","NotoSerifDevanagari.zip":"Noto Serif Devanagari","NotoSerifDisplay.zip":"Noto Serif Display","NotoSerifDogra.zip":"Noto Serif Dogra","NotoSerifEthiopic.zip":"Noto Serif Ethiopic","NotoSerifGeorgian.zip":"Noto Serif Georgian","NotoSerifGrantha.zip":"Noto Serif Grantha","NotoSerifGujarati.zip":"Noto Serif Gujarati","NotoSerifGurmukhi.zip":"Noto Serif Gurmukhi","NotoSerifHebrew.zip":"Noto Serif Hebrew","NotoSerifKannada.zip":"Noto Serif Kannada","NotoSerifKhmer.zip":"Noto Serif Khmer","NotoSerifKhojki.zip":"Noto Serif Khojki","NotoSerifLao.zip":"Noto Serif Lao","NotoSerifMalayalam.zip":"Noto Serif Malayalam","NotoSerifMyanmar.zip":"Noto Serif Myanmar","NotoSerifNyiakengPuachueHmong.zip":"Noto Serif Nyiakeng Puachue Hmong","NotoSerifOriya.zip":"Noto Serif Oriya","NotoSerifSinhala.zip":"Noto Serif Sinhala","NotoSerifTamil.zip":"Noto Serif Tamil","NotoSerifTamilSlanted.zip":"Noto Serif Tamil Slanted","NotoSerifTangut.zip":"Noto Serif Tangut","NotoSerifTelugu.zip":"Noto Serif Telugu","NotoSerifThai.zip":"Noto Serif Thai","NotoSerifTibetan.zip":"Noto Serif Tibetan","NotoSerifVithkuqi.zip":"Noto Serif Vithkuqi","NotoSerifYezidi.zip":"Noto Serif Yezidi","NotoTraditionalNushu.zip":"Noto Traditional Nushu"}';

		$return_array = json_decode( $font_json, true );

		if ( $cleaned_alphabetical ){

			$cleaned_array = array();
			foreach ( $return_array as $zip_name => $font_name ){

				$cleaned_name = $font_name;
				if ( str_starts_with( $font_name, 'Noto Sans' ) ) {
					$cleaned_name = substr( $cleaned_name, 10 ) . ' (Noto Sans)';
				} elseif ( str_starts_with( $font_name, 'Noto Serif' ) ) {
					$cleaned_name = substr( $cleaned_name, 11 ) . ' (Noto Serif)';
				}

				// special cases here (lets us keep our font array clean but show more info in UI)
				switch ( $cleaned_name ){

					case 'Boku2':
						$cleaned_name = 'Boku2 (JP)';
						break;
					case 'Hong Kong (Noto Sans)':
						$cleaned_name = 'Chinese (Traditional, Hong Kong)';
						break;
					case 'Taiwan (Noto Sans)':
						$cleaned_name = 'Chinese (Traditional, Taiwan)';
						break;
					case 'Simplified Chinese (Noto Sans)':
						$cleaned_name = 'Chinese (Simplified)';
						break;

				}

				$cleaned_array[ $font_name ] = $cleaned_name;

			}

			// sort alphabetically
			asort( $cleaned_array );

			return $cleaned_array;

		}

		return $return_array;

	}

	/*
	* Converts a font-name to its zip filename
	*/
	public function zip_to_font_name( $zip_file_name='' ){

		return str_replace( '.zip', '',  jpcrm_string_split_at_caps( $zip_file_name ) );

	}

	/*
	* Converts a font-name to its zip filename
	*/
	public function font_name_to_zip( $font_name='' ){

		return str_replace( ' ', '',  $font_name ) . '.zip';

	}

	/*
	* Converts a font-name to its *-Regular.ttf filename
	*/
	public function font_name_to_regular_ttf_name( $font_name='' ){

		return str_replace( ' ', '',  $font_name ) . '-Regular.ttf';

	}

	/*
	* Converts a font-name to its ultimate directory
	*/
	public function font_name_to_dir( $font_name='' ){

		return str_replace( '.zip', '', $this->font_name_to_zip( $font_name ) );

	}

	/*
	* Converts a slug to a font name
	*/
	public function font_slug_to_name( $font_slug='' ){

		return ucwords( str_replace( '-', ' ', $font_slug ) );

	}

	/*
	* Converts a font-name to a slug equivalent
	*/
	public function font_name_to_slug( $font_name='' ){

		global $zbs;
		return $zbs->DAL->makeSlug( $font_name );

	}

	/*
	* Checks a font is on our available list
	*/
	public function font_is_available( $font_name='' ){

		$fonts = $this->list_all_available();

		if ( isset( $fonts[ $this->font_name_to_zip( $font_name ) ] ) ) {
			
			return true;

		}

		return false;

	}

	/*
	* Checks a font is on our available list
	*/
	public function font_is_installed( $font_name='' ){

		if ( $this->font_is_available( $font_name ) ){

			// Available?
			if ( file_exists( ZEROBSCRM_INCLUDE_PATH . 'lib/dompdf-fonts/' . $this->font_name_to_dir( $font_name ) ) ){

				// Installed? (check setting)
				$font_install_setting = zeroBSCRM_getSetting('pdf_extra_fonts_installed');
				if ( !is_array( $font_install_setting ) ){
					$font_install_setting = array();
				}

				if ( array_key_exists( $this->font_name_to_slug( $font_name ), $font_install_setting ) ){
					return true;
				}

			} 

		}

		return false; // font doesn't exist or isn't installed

	}


	/*
	* Installs fonts (which have already been downloaded, but are not marked installed)
	*/
	public function install_font( $font_name='', $force_reinstall=false ) {

		// is available, and not installed (or $force_reinstall)
		if ( 
			$this->font_is_available( $font_name ) && 
			( !$this->font_is_installed( $font_name ) || $force_reinstall )
		) {

			// get fonts dir
			$fonts_dir = jpcrm_storage_fonts_dir_path();
			$working_dir = zeroBSCRM_privatisedDirCheckWorks();

			// Check if temp dir is valid
			if ( empty( $working_dir['path'] ) || !$fonts_dir ) {
				return false;
			}

			$working_dir = $working_dir['path'] . '/';

			$font_directory_name = $this->font_name_to_dir( $font_name );

			// Discern available variations
			$font_regular_path = $working_dir . $this->font_name_to_regular_ttf_name( $font_name ); // 'NotoSans-Regular.ttf' - ALL variations have a `*-Regular.ttf` as at 01/12/21
			$font_bold_path = null;
			$font_italic_path = null;
			$font_bolditalic_path = null;

			if ( file_exists( $working_dir . $font_directory_name . '-Bold.ttf' ) ){
				$font_bold_path = $working_dir . $font_directory_name . '-Bold.ttf';
			}
			if ( file_exists( $working_dir . $font_directory_name . '-Italic.ttf' ) ){
				$font_italic_path = $working_dir . $font_directory_name . '-Italic.ttf';
			}
			if ( file_exists( $working_dir . $font_directory_name . '-BoldItalic.ttf' ) ){
				$font_bolditalic_path = $working_dir . $font_directory_name . '-BoldItalic.ttf';
			}

			// Attempt to install
			if ($this->load_font(
			    str_replace( ' ' ,'', $font_name ), // e.g. NotoSansJP
			    $font_regular_path,
			    $font_bold_path,
			    $font_italic_path,
			    $font_bolditalic_path
			  )){

			  	global $zbs;

				// Update setting
				$font_install_setting = $zbs->settings->get('pdf_extra_fonts_installed');
				if ( !is_array( $font_install_setting ) ){
					$font_install_setting = array();
				}
				$font_install_setting[ $this->font_name_to_slug( $font_name ) ] = time();
				$zbs->settings->update( 'pdf_extra_fonts_installed', $font_install_setting );

				return true;

			}

		}

		return false;

	}


	/*
	* Installs default fonts (which are extracted, but are not marked installed)
	* can use $this->extract_and_install_default_fonts() if from scratch (extracts + installs)
	*/
	public function install_default_fonts( $force_reinstall = false ){

		global $zbsExtensionInstallError;

		// get fonts dir
		$fonts_dir = jpcrm_storage_fonts_dir_path();
		$working_dir = zeroBSCRM_privatisedDirCheckWorks();

		// Check if temp dir is valid
		if ( empty( $working_dir['path'] ) || !$fonts_dir ) {
			$zbsExtensionInstallError = __( 'Jetpack CRM was not able to create the directories it needs in order to install fonts for the PDF Engine.', 'zero-bs-crm' );
			return false;
		}

		$working_dir = $working_dir['path'] . '/';

		// also install the font(s) if not already installed (if present)
		$fontsInstalled = zeroBSCRM_getSetting('pdf_fonts_installed');
		if (
			( $fontsInstalled !== 1 && file_exists( $fonts_dir . 'fonts-info.txt' ) )
			||
			( !$this->default_fonts_installed() )
			||
			$force_reinstall
		) {

			// attempt to install
			if (
				$this->load_font(
					'NotoSansGlobal',
					$working_dir . 'NotoSans-Regular.ttf',
					$working_dir . 'NotoSans-Bold.ttf',
					$working_dir . 'NotoSans-Italic.ttf',
					$working_dir . 'NotoSans-BoldItalic.ttf'
				)
			) {
				// update setting
				global $zbs;
				$zbs->settings->update( 'pdf_fonts_installed', 1 );

			} else {
				$zbsExtensionInstallError = __( 'Jetpack CRM was not able to install fonts for the PDF engine.', 'zero-bs-crm' );
				return false;
			}

		}

		return true;

	}


	/**
	 * Installs a new font family
	 * This function maps a font-family name to a font.  It tries to locate the
	 * bold, italic, and bold italic versions of the font as well.  Once the
	 * files are located, ttf versions of the font are copied to the fonts
	 * directory.  Changes to the font lookup table are saved to the cache.
	 *
	 * This is an an adapted version of install_font_family() from https://github.com/dompdf/utils
	 *
	 * @param Dompdf      $dompdf dompdf main object.
	 * @param string      $fontname the font-family name.
	 * @param string      $normal the filename of the normal face font subtype.
	 * @param string|null $bold the filename of the bold face font subtype.
	 * @param string|null $italic the filename of the italic face font subtype.
	 * @param string|null $bold_italic the filename of the bold italic face font subtype.
	 *
	 * @throws Exception
	 */
	public function install_font_family($dompdf, $fontname, $normal, $bold = null, $italic = null, $bold_italic = null, $debug = false) {

	  try {

			$fontMetrics = $dompdf->getFontMetrics();

			// Check if the base filename is readable
			if ( !is_readable($normal) ) {
				throw new Exception("Unable to read '$normal'.");
			}

			$dir = dirname($normal);
			$basename = basename($normal);
			$last_dot = strrpos($basename, '.');
			if ($last_dot !== false) {
				$file = substr($basename, 0, $last_dot);
				$ext = strtolower(substr($basename, $last_dot));
			} else {
				$file = $basename;
				$ext = '';
			}

			// dompdf will eventually support .otf, but for now limit to .ttf
			if ( !in_array($ext, array(".ttf")) ) {
				throw new Exception("Unable to process fonts of type '$ext'.");
			}

			// Try $file_Bold.$ext etc.
			$path = "$dir/$file";

			$patterns = array(
				"bold"        => array("_Bold", "b", "B", "bd", "BD"),
				"italic"      => array("_Italic", "i", "I"),
				"bold_italic" => array("_Bold_Italic", "bi", "BI", "ib", "IB"),
			);

			foreach ($patterns as $type => $_patterns) {
				if ( !isset($$type) || !is_readable($$type) ) {
					foreach($_patterns as $_pattern) {
						if ( is_readable("$path$_pattern$ext") ) {
							$$type = "$path$_pattern$ext";
							break;
						}
					}

					if ( is_null($$type) )
						if ($debug) echo ("Unable to find $type face file.\n");
				}
			}

			$fonts = compact("normal", "bold", "italic", "bold_italic");
			$entry = array();

			// Copy the files to the font directory.
			foreach ($fonts as $var => $src) {
				if ( is_null($src) ) {
					$entry[$var] = $dompdf->getOptions()->get('fontDir') . '/' . mb_substr(basename($normal), 0, -4);
					continue;
				}

				// Verify that the fonts exist and are readable
				if ( !is_readable($src) ) {
					throw new Exception("Requested font '$src' is not readable");
				}

				$dest = $dompdf->getOptions()->get('fontDir') . '/' . basename($src);

				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable, Generic.WhiteSpace.ScopeIndent.IncorrectExact -- TODO: Fix these.
				if ( ! is_writable( dirname( $dest ) ) ) {
					throw new Exception("Unable to write to destination '$dest'.");
				}

				if ($debug) echo "Copying $src to $dest...\n";

				if ( !copy($src, $dest) ) {
					throw new Exception("Unable to copy '$src' to '$dest'");
				}

				$entry_name = mb_substr($dest, 0, -4);
				
				if ($debug) echo "Generating Adobe Font Metrics for $entry_name...\n";
				
				$font_obj = Font::load($dest);
				$font_obj->saveAdobeFontMetrics("$entry_name.ufm");
				$font_obj->close();

				$entry[$var] = $entry_name;

				unlink( $src );

			}

			// Store the fonts in the lookup table
			$fontMetrics->setFontFamily($fontname, $entry);

			// Save the changes
			$fontMetrics->saveFontFamilies();

			// Fini
			return true;

		} catch (Exception $e){

			// nada

		}

		return false;

	}

	/*
	* Retrieves a font zip from our CDN and installs it locally
	*/
	public function retrieve_and_install_specific_font( $font_name='', $force_reinstall = false){

		global $zbsExtensionInstallError;

		// font exists?
		if ( !$this->font_is_available( $font_name) ){

			return false;

		}

		// font already installed?
		if ( $this->font_is_installed( $font_name ) && !$force_reinstall ){

			return true;

		}

		// get fonts dir
		$fonts_dir = jpcrm_storage_fonts_dir_path();
		$working_dir = zeroBSCRM_privatisedDirCheckWorks();

		// Check if temp dir is valid
		if ( empty( $working_dir['path'] ) || !$fonts_dir ) {
			$zbsExtensionInstallError = __( 'Jetpack CRM was not able to create the directories it needs in order to install fonts for the PDF Engine.', 'zero-bs-crm' );
			return false;
		}

		$working_dir = $working_dir['path'] . '/';
		$font_file_name = $this->font_name_to_regular_ttf_name( $font_name );

		// Already downloaded, so proceed to install
		if ( file_exists( $working_dir . $font_file_name) ) {
			return $this->install_font( $font_name );
		}

		// Retrieve & install the font
		$remote_zip_name = $this->font_name_to_zip( $font_name );
		$temp_path = tempnam( sys_get_temp_dir(), 'crmfont' );

		// Several large font files may timeout when downloading. Increase the timeout for these files.
		$large_font_files = array( ' Noto  Sans  Simplified  Chinese', ' Noto  Serif  Display', ' Noto  Sans', ' Noto  Sans  Display', ' Noto  Sans  Taiwan', ' Noto  Sans  Hong  Kong', ' Noto  Sans  Japanese', ' Noto  Sans  Korean', ' Noto  Sans  Mono' );
		if ( in_array( $font_name, $large_font_files, true ) ) {
			add_filter(
				'http_request_timeout',
				function () {
					return 60;
				}
			);
		}

		// Retrieve zip
		global $zbs;
		if ( !zeroBSCRM_retrieveFile( $zbs->urls['extdlfonts'] . $remote_zip_name, $temp_path ) ) {
			// Something failed
			$zbsExtensionInstallError = __('Jetpack CRM was not able to download the fonts it needs for the PDF Engine.',"zero-bs-crm").' '.__('(fonts)','zero-bs-crm');
			unlink( $temp_path );
			return false;
		}

		// Extract zip to fonts dir
		$expanded = zeroBSCRM_expandArchive( $temp_path, $working_dir );
		unlink( $temp_path );

		// appears to have worked
		if ( !$expanded || !file_exists( $working_dir . $font_file_name ) ) {

			// Add error msg
			$zbsExtensionInstallError = __('CRM was not able to retrieve the requested font.',"zero-bs-crm") . ' ' . __('(Failed to install font.)',"zero-bs-crm");
			##WLREMOVE
			$zbsExtensionInstallError = __('Jetpack CRM was not able to retrieve the requested font.',"zero-bs-crm") . ' ' . __('(Failed to install font.)',"zero-bs-crm");
			##/WLREMOVE

			return false;

		}

		// install the font
		return $this->install_font( $font_name, true );

	}


	/*
	* Extract (and install) default fonts which dompdf uses to provide global lang supp
	* This function is used if somebody were to delete these default fonts from jpcrm-storage.
	* Instead, use retrieve_and_install() to retrieve locale specific fonts (from v4.7.0)
	*/
	public function extract_and_install_default_fonts(){

		global $zbsExtensionInstallError;

		// get fonts dir
		$fonts_dir = jpcrm_storage_fonts_dir_path();
		$working_dir = zeroBSCRM_privatisedDirCheckWorks();

		// Check if temp dir is valid
		if ( empty( $working_dir['path'] ) || !$fonts_dir ) {
			$zbsExtensionInstallError = __( 'Jetpack CRM was not able to create the directories it needs in order to install fonts for the PDF Engine.', 'zero-bs-crm' );
			return false;
		}

		$working_dir = $working_dir['path'] . '/';

		// Check if fonts are already downloaded
		if ( file_exists( $fonts_dir . 'fonts-info.txt' ) ) {
			// Proceed to installation
			return $this->install_default_fonts();
		}

		// Extract zip to fonts dir
		$expanded = zeroBSCRM_expandArchive( ZEROBSCRM_PATH . 'data/pdffonts.zip', $working_dir );

		// Check success?
		if ( !$expanded || !file_exists( $working_dir . 'fonts-info.txt' ) ) {

			// Add error msg
			$zbsExtensionInstallError = __('Jetpack CRM was not able to extract the fonts it needs for the PDF Engine.',"zero-bs-crm").' '.__('(fonts)','zero-bs-crm');
			// Return fail
			return false;

		}

		rename( $working_dir . 'fonts-info.txt', $fonts_dir . 'fonts-info.txt' );

		// install 'em
		return $this->install_default_fonts( true );


	}

	/**
	 * Loads a font file collection (.ttf's) onto the server for dompdf
	 * only needs to fire once
	 *
	 * @param string      $font_name Font name.
	 * @param string      $regular_file Regular font file.
	 * @param string|null $bold_file Bold font file.
	 * @param string|null $italic_file Italic font file.
	 * @param string|null $bold_italic_file Bold italic font file.
	 */
	public function load_font( $font_name = '', $regular_file = '', $bold_file = null, $italic_file = null, $bold_italic_file = null ) {

		if (
			zeroBSCRM_isZBSAdminOrAdmin()
			&& ! empty( $font_name )
			&& file_exists( $regular_file )
			&& ( $bold_file === null || file_exists( $bold_file ) )
			&& ( $italic_file === null || file_exists( $italic_file ) )
			&& ( $bold_italic_file === null || file_exists( $bold_italic_file ) )
		) {

			// PDF Install check (importantly skip the font check with false first param)
			zeroBSCRM_extension_checkinstall_pdfinv( false );

			global $zbs;
			$dompdf = $zbs->pdf_engine();

			// Install the font(s)
			return $this->install_font_family( $dompdf, $font_name, $regular_file, $bold_file, $italic_file, $bold_italic_file );

		}

		return false;
	}

	/*
	* Retrieves the font cache from dompdf and returns all loaded fonts
	*/
	public function loaded_fonts(){

		$dompdf_font_cache_file = jpcrm_storage_fonts_dir_path() . 'installed-fonts.json';

		if ( file_exists( $dompdf_font_cache_file ) ) {

			$cacheData = json_decode( file_get_contents( $dompdf_font_cache_file ) );

			return $cacheData;

		}

		return array();

	}

	/*
	* Returns bool whether or not our key font (Noto Sans global) is installed according to dompdf
	*/
	public function default_fonts_installed(){

		$existing_fonts = $this->loaded_fonts();

		if ( isset( $existing_fonts->notosansglobal ) ){

			return true;
		}

		return false;
	}
}
