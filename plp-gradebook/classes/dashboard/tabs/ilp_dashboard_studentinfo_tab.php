<?php

//require the ilp_plugin.php class
require_once($CFG->dirroot.'/blocks/ilp/classes/dashboard/ilp_dashboard_tab.php');

class ilp_dashboard_studentinfo_tab extends ilp_dashboard_tab {

    public		$student_id;
    public 		$filepath;
    public		$linkurl;
    public 		$selectedtab;

    function __construct($student_id=null,$course_id=NULL,$studentArray=NULL)	{
        global 	$CFG;

        $this->linkurl				=	$CFG->wwwroot."/blocks/ilp/actions/view_main.php?user_id=".$student_id."&course_id={$course_id}";

        $this->student_id	=	$student_id;
        $this->course_id	=	$course_id;
        $this->filepath		=	$CFG->dirroot."/blocks/ilp/classes/dashboard/tabs/ilp_dashboard_studentinfo_tab.php";

        //set the id of the tab that will be displayed first as default
        $this->default_tab_id	=	$this->plugin_id.'-1';

        //call the parent constructor
        parent::__construct();
    }

    /**
     * Return the text to be displayed on the tab
     */
    function display_name()	{
        return	get_string('ilp_dashboard_studentinfo_tab_name','block_ilp');
    }


    /**
     * Returns the content to be displayed
     *
     * @param	string $selectedtab the tab that has been selected this variable
     * this variable should be used to determined what to display
     *
     * @return none
     */
    function display($selectedtab=null)	{
        global 	$CFG, $PAGE, $USER, $OUTPUT, $PARSER;

        //get the selecttab param if has been set
        $this->selectedtab = $PARSER->optional_param('selectedtab', NULL, PARAM_INT);

        //get the tabitem param if has been set
        $this->tabitem = $PARSER->optional_param('tabitem', NULL, PARAM_CLEAN);

        //split the selected tab id on up 3 ':'
        $seltab	=	explode(':',$selectedtab);

        //if the seltab is empty then the highest level tab has been selected
        if (empty($seltab))	$seltab	=	array($selectedtab);


        $pluginoutput	=	"";

        if ($this->dbc->get_user_by_id($this->student_id)) {
            //start buffering output
            ob_start();

            $this->ilp_display_studentinfo();

//            if (!empty($seltab[1]))	{
//                switch ($seltab[1]) {
//
//                    case 1:
//                        $this->ilp_display_studentinfo();
//                        break;
//
//                    default:
//                        $this->ilp_display_studentinfo();
//                        break;
//                }
//            }
            // load custom javascript
            $module = array(
                'name'      => 'ilp_dashboard_exams_tab',
                'fullpath'  => '/blocks/ilp/classes/dashboard/tabs/ilp_dashboard_archive_tab.js',
                'requires'  => array('yui2-dom', 'yui2-event', 'yui2-connection', 'yui2-container', 'yui2-animation')
            );

            // js arguments
            $jsarguments = array(
                'open_image'   => $CFG->wwwroot."/blocks/ilp/pix/icons/switch_minus.gif",
                'closed_image' => $CFG->wwwroot."/blocks/ilp/pix/icons/switch_plus.gif",
            );

            // initialise the js for the page
            $PAGE->requires->js_init_call('M.ilp_dashboard_archive_tab.init', $jsarguments, true, $module);

            $pluginoutput = ob_get_contents();

            ob_end_clean();


        } else {
            $pluginoutput	=	get_string('studentnotfound','block_ilp');
        }

        return $pluginoutput;
    }


    function ilp_display_studentinfo()	{
        global	$CFG;

        include($CFG->dirroot.'/blocks/ilp/classes/dashboard/tabs/ilp_dashboard_studentinfo_tab.html');
    }



    /**
     * Adds the string values from the tab to the language file
     *
     * @param	array &$string the language strings array passed by reference so we
     * just need to simply add the plugins entries on to it
     */
    function language_strings(&$string) {
        $string['ilp_dashboard_studentinfo_tab'] 					= 'Student info';
        $string['ilp_dashboard_studentinfo_tab_name'] 				= 'Student info';


        return $string;
    }


}
