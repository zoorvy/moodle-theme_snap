<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Layout - faux site index.
 * This layout is baed on a moodle site index.php file but has been adapted to show news items in a different
 * way.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    $frontpagelayout = $CFG->frontpageloggedin;
} else {
    $frontpagelayout = $CFG->frontpage;
}
$CFG->frontpage = '';
$CFG->frontpageloggedin = '';

// Remove final closing tag to insert more content.
$lastclosetag = '</div>';
$maincontent = $OUTPUT->main_content();
if (substr_compare($maincontent, $lastclosetag, -strlen($lastclosetag)) !== 0) {
    $message = 'Main content does not have the expected div tag wrapping it, required for Snap Site News.';
    throw new coding_exception($message);
}
echo substr($maincontent, 0, -strlen($lastclosetag));
$courserenderer = $PAGE->get_renderer('core', 'course');

/* Duplicates code from index.php which outputs front page items
 * to allow us to override the front page news.
 * */
foreach (explode(',',$frontpagelayout) as $v) {
    switch ($v) {     /// Display the main part of the front page.
        case FRONTPAGENEWS:
            if ($SITE->newsitems) { // Print forums only when needed
                // Snap specific override.
                echo $OUTPUT->site_frontpage_news();
            }
        break;

    case FRONTPAGEENROLLEDCOURSELIST:
        // echo "<a href='http://joule2.dev/#primary-nav'>My courses</a>";

        $mycourseshtml = $courserenderer->frontpage_my_courses();
        if (!empty($mycourseshtml)) {
            echo html_writer::tag('a', get_string('skipa', 'access', core_text::strtolower(get_string('mycourses'))), array('href'=>'#skipmycourses', 'class'=>'skip-block'));

            //wrap frontpage course list in div container
            echo html_writer::start_tag('div', array('id'=>'frontpage-course-list'));

            echo $OUTPUT->heading(get_string('mycourses'));
            echo $mycourseshtml;

            //end frontpage course list div container
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipmycourses'));

            break;
        }
        // No "break" here. If there are no enrolled courses - continue to 'Available courses'.

    case FRONTPAGEALLCOURSELIST:
        $availablecourseshtml = $courserenderer->frontpage_available_courses();
        if (!empty($availablecourseshtml)) {
            echo html_writer::tag('a', get_string('skipa', 'access', core_text::strtolower(get_string('availablecourses'))), array('href'=>'#skipavailablecourses', 'class'=>'skip-block'));

            //wrap frontpage course list in div container
            echo html_writer::start_tag('div', array('id'=>'frontpage-course-list'));

            echo $OUTPUT->heading(get_string('availablecourses'));
            echo $availablecourseshtml;

            //end frontpage course list div container
            echo html_writer::end_tag('div');

            echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipavailablecourses'));
        }
    break;

    case FRONTPAGECATEGORYNAMES:
        echo html_writer::tag('a', get_string('skipa', 'access', core_text::strtolower(get_string('categories'))), array('href'=>'#skipcategories', 'class'=>'skip-block'));

        //wrap frontpage category names in div container
        echo html_writer::start_tag('div', array('id'=>'frontpage-category-names'));

        echo $OUTPUT->heading(get_string('categories'));
        echo $courserenderer->frontpage_categories_list();

        //end frontpage category names div container
        echo html_writer::end_tag('div');

        echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipcategories'));
    break;

    case FRONTPAGECATEGORYCOMBO:
        echo html_writer::tag('a', get_string('skipa', 'access', core_text::strtolower(get_string('courses'))), array('href'=>'#skipcourses', 'class'=>'skip-block'));

        //wrap frontpage category combo in div container
        echo html_writer::start_tag('div', array('id'=>'frontpage-category-combo'));

        echo $OUTPUT->heading(get_string('courses'));
        echo $courserenderer->frontpage_combo_list();

        //end frontpage category combo div container
        echo html_writer::end_tag('div');

        echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipcourses'));
    break;

    case FRONTPAGECOURSESEARCH:
        echo $OUTPUT->box($courserenderer->course_search_form('', 'short'), 'mdl-align');
    break;
    }
    echo $lastclosetag;
}
