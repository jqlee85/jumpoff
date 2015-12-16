<?php

/**
 * Fired during plugin activation
 *
 * @link       http://jessequinnlee.com
 * @since      1.0.0
 *
 * @package    Jumpoff
 * @subpackage Jumpoff/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jumpoff
 * @subpackage Jumpoff/includes
 * @author     Jesse Lee <jesse@jessequinnlee.com>
 */
class Jumpoff_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/*---------- create database table for prompt words ---------------*/
		//accepts id, word, and class as arguments, inserts to db
		function jo_insert_word_to_db($word_id, $word_word, $word_class, $table_name) {
			global $wpdb;
			$wpdb->insert( 
				$table_name, 
				array( 
					'id' => $word_id,
					'word' => $word_word, 
					'word_class' => $word_class
				)
			);
		}

		//creates table, fills db with words
		function jo_initialize_db() {
			//initialize db
			global $jo_db_version;
			$jo_db_version = '1.0';

			global $wpdb;
			global $jo_db_version;

		  	$table_name = $wpdb->prefix . "jo_prompts"; 

		  	$charset_collate = $wpdb->get_charset_collate();

		  	$sql_remove_table = "DROP TABLE $table_name;";

			$sql = "CREATE TABLE $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  word tinytext NOT NULL,
			  word_class tinytext NOT NULL,
			  UNIQUE KEY id (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql_remove_table );
			dbDelta( $sql );

			add_option( 'jo_db_version', $jo_db_version );
			
			//add words to table

			//create verb array
			$verb_list = array(
				'surf','turn','kiss','brace','run','embellish','transfer','push','think','love','jump','type','play','sue','cut','expand','dress up','win','lose','pull','redirect','sail','rake','yell','whisper','shade','draw','bathe','clean','estimate','improvise','listen','speak','translate','empathize','free','defend','attack','float','swim','sink','dive','paint','stretch','blow','weigh','pack','wrap','stack','sprint','cruise','extend','grow','plunge','glow','purchase','disappear','hug','invite','employ','quit','shake','inspect','cook','prepare','bake','thaw','boil','store','rate','describe','deconstruct','consider','reverse','revert','score','build','assemble','cover','stain','fry','forage','gather','rest','assault','agree','rekindle','taste','smell','sniff','inhale','hear','careen','blast','charge','scoot','soar','amble','ski','skate','sink','berate','insult','compliment','convince','deceive','inform','comfort','collapse'
			);

			//create noun array
			$noun_list = array(
				'board','plank','workout','tennis racquet','beer','window','door','face','body','foot','hand','envelope','pencil','pen','hair','session','lips','shin','home','department','apartment','house','brink','brick','stairway','skeleton','lawsuit','relationship','core','fruit','spaghetti','sandwich','turkey','basketball','surfboard','wave','conversation','experience','telephone','computer','tablet','pill','medication','prescription','examination','homework','novel','manuscript','banana','papaya','porch','boat','car','cabin','basement','heater','room','block','town','city','state','country','nation','fish','shark','dolphin','coral','bear','code','cord','pipe','scripture','sermon','savior','religion','belief','atheist','skeptic','interest','elbow','elephant','position','court','field','lawn','teacher','principal','judge','lawyer','doctor','president','senator','liar','politician','celebrity','stud','beauty','painting','sculpture'
			);
			
			$counter = 1;
			//insert verbs
			foreach ($verb_list as $value) {
				jo_insert_word_to_db($counter, $value, 'verb', $table_name);
				$counter++;
			}

			//insert verbs
			foreach ($noun_list as $key => $value) {
				jo_insert_word_to_db($counter, $value, 'noun', $table_name);
				$counter++;
			}

		}
		/*---------- /create database table for prompt words ---------------*/






	}

}
