<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Activities Model
 *
 * This script is the model for the Camper activities and member operations, from rosters
 * to activity registration for the admin and leader sides.
 *
 * Version 1.4 (2014.02.24.1400)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
*/

class Activities_model extends CI_Model {

	public function __construct()
	{
		
	}

	/*
	 *
	 *	Members Functions
	 *	Methods used for the members sections of the admin and leader views
	 *
	 */

	// Get members
	public function get_members($unit=FALSE,$relational=FALSE)
	{
		// Get our unit members if unit is set
		if ($unit === FALSE) {
			$query = $this->db->get('members');
		} else {
			$query = $this->db->get_where('members', array('unit'=>$unit));
		}
		
		// Return normal or relational array
		if ($relational === false) {
			return $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $u) { $result[$u['id']] = $u;}
			return $result;
		}
	}

	// Get a single member by id
	public function get_member($id)
	{
		$query = $this->db->get_where('members', array('id' => $id));
		return $query->row_array();		
	}

	// Delete a single member by id
	public function delete_member($id)
	{
		// Delete activity and roster dependencies
		$tables = array('roster', 'classregs');
		$this->db->where('member', $id);
		$this->db->delete($tables);

		// Delete our member
		$this->db->delete('members', array('id' => $id));
		return true;		
	}

	// Delete a single member by id
	public function update_member($id, $details=FALSE)
	{
		// Use post or $details for the member
		if ($details === false) {
			// Get our details from the post vars
			$new = $this->input->post('member');
		} else {
			// Use our supplied detail array
			$new = $details;
		}
		
		// Get the original
		$member = $this->get_member($id);
		if (!isset($member['id']) || empty($member['id'])) return false;
		
		// Handle our new details
		if (isset($new['name']) && $member['name'] !== $new['name']) $memberdata['name'] = $this->shared->prep_option($new['name'],'text');
		if (isset($new['address']) && $member['address'] !== $new['address']) $memberdata['address'] = (empty($new['address']) && $new['address'] !== '') ? null: $this->shared->prep_option($new['address'],'text');
		if (isset($new['citystate']) && $member['citystate'] !== $new['citystate']) $memberdata['citystate'] = (empty($new['citystate']) && $new['citystate'] !== '') ? null: $this->shared->prep_option($new['citystate'],'text');
		if (isset($new['insurance']) && $member['insurance'] !== $new['insurance']) $memberdata['insurance'] = (empty($new['insurance']) && $new['insurance'] !== '') ? null: $this->shared->prep_option($new['insurance'],'text');
		if (isset($new['phone']) && $member['phone'] !== $new['phone']) $memberdata['phone'] = (empty($new['phone']) && $new['phone'] !== '') ? null: $this->shared->prep_option($new['phone'],'text');
		if (isset($new['dob']) && $member['dob'] !== $this->shared->prep_option($new['dob'],'date')) $memberdata['dob'] = $this->shared->prep_option($new['dob'],'date');
		if (isset($new['unit']) && $member['unit'] !== $new['unit']) $memberdata['unit'] = $this->shared->prep_option($new['unit'],'number');
		if (isset($new['notes']) && $member['notes'] !== $new['notes']) $memberdata['notes'] = (empty($new['notes']) && $new['notes'] !== '') ? null: $this->shared->prep_option($new['notes'],'text');
		if (isset($new['diet']) && $member['diet'] !== $new['diet']) $memberdata['diet'] = (empty($new['diet']) && $new['diet'] !== '') ? null: $this->shared->prep_option($new['diet'],'text');
		if (isset($new['shirtsize']) && $member['shirtsize'] !== $new['shirtsize']) $memberdata['shirtsize'] = (empty($new['shirtsize']) && $new['shirtsize'] !== '') ? null: $this->shared->prep_option($new['shirtsize'],'text');
		if (isset($new['medical']) && $member['medical'] !== $new['medical']) $memberdata['medical'] = (empty($new['medical']) && $new['medical'] !== '') ? null: $this->shared->prep_option($new['medical'],'text');
		if (isset($new['allergies']) && $member['allergies'] !== $new['allergies']) $memberdata['allergies'] = (empty($new['allergies']) && $new['allergies'] !== '') ? null: $this->shared->prep_option($new['allergies'],'text');
		if (isset($new['gender']) && $member['gender'] !== $new['gender']) $memberdata['gender'] = $this->shared->prep_option($new['gender'],'text');
		
		// Update the member
		if (isset($memberdata)) {
			$this->db->where('id', $id);
			$this->db->update('members', $memberdata);
			if ($this->input->get_post('return')) {
				$this->session->set_flashdata('message', $member['name'].' has been updated.');
				redirect($this->input->get_post('return'), 'refresh');
			}
			return true;
		}
		return false;
	}

	/* Create a member
	 * $details = (FALSE) use post data 
	 * $details = ($batch=false) array(name,address,citystate,phone,dob(unix),unit,notes,diet,shirtsize,medical,gender)
	 * $details = ($batch=true) array(array(name,etc...),[...]) array of arrays of users
	 * $unit = (FALSE) get the user's unit id or specify unit id
	 * returns false for fail or error, true for batch success, id for single success
	 */
	public function create_member($details=FALSE,$batch=FALSE,$unit=FALSE)
	{
		// Setup
		$unit = ($unit === false) ? $this->shared->get_user_unit(false,'id'): $unit;
		$timestamp = time();

		// Here we go
		if ($batch === false) {
			// We are just creating one user
			if ($details === false) {
				// Get our details from the post vars
				$member = $this->input->post('member');
			} else {
				// Use our supplied detail array
				$member = $details;
			}
			if (!$member || !isset($member['name']) || !isset($member['dob']) || !isset($member['gender'])) return $errors['error'] = 'The member name, address and gender are all required.';
	
			/* Process the details
			$memberdata = array(
				'name' 		=> $this->shared->prep_option($member['name'],'text'),
				'address' 	=> (isset($member['address']) && $member['address'] !== '') ? $this->shared->prep_option($member['address'],'text'): null,
				'citystate' => (isset($member['citystate']) && $member['citystate'] !== '') ? $this->shared->prep_option($member['citystate'],'text'): null,
				'insurance' => (isset($member['insurance']) && $member['insurance'] !== '') ? $this->shared->prep_option($member['insurance'],'text'): null,
				'phone' 	=> (isset($member['phone']) && $member['phone'] !== '') ? $this->shared->prep_option($member['phone'],'text'): null,
				'dob' 		=> $this->shared->prep_option($member['dob'],'date'),
				'unit' 		=> $unit,
				'notes' 	=> (isset($member['notes']) && $member['notes'] !== '') ? $this->shared->prep_option($member['notes'],'text'): null,
				'diet' 		=> (isset($member['diet']) && $member['diet'] !== '') ? $this->shared->prep_option($member['diet'],'text'): null,
				'shirtsize' => (isset($member['shirtsize']) && $member['shirtsize'] !== '') ? $this->shared->prep_option($member['shirtsize'],'text'): null,
				'medical' 	=> (isset($member['medical']) && $member['medical'] !== '') ? $this->shared->prep_option($member['medical'],'text'): null,
				'allergies' => (isset($member['allergies']) && $member['allergies'] !== '') ? $this->shared->prep_option($member['allergies'],'text'): null,
				'gender' 	=> $this->shared->prep_option($member['gender'],'text'),
				'created' 	=> $timestamp
			);*/
			
			$this->db->set('name', $this->shared->prep_option($member['name'],'text'));
			$this->db->set('dob', $this->shared->prep_option($member['dob'],'date'));
			$this->db->set('gender', $this->shared->prep_option($member['gender'],'date'));
			$this->db->set('unit', $unit);
			$this->db->set('created', $timestamp);
			if (isset($member['address']) && $member['address'] !== '') $this->db->set('address', $this->shared->prep_option($member['address'],'text'));
			if (isset($member['citystate']) && $member['citystate'] !== '') $this->db->set('citystate', $this->shared->prep_option($member['citystate'],'text'));
			if (isset($member['insurance']) && $member['insurance'] !== '') $this->db->set('insurance', $this->shared->prep_option($member['insurance'],'text'));
			if (isset($member['phone']) && $member['phone'] !== '') $this->db->set('phone', $this->shared->prep_option($member['phone'],'text'));
			if (isset($member['notes']) && $member['notes'] !== '') $this->db->set('notes', $this->shared->prep_option($member['notes'],'text'));
			if (isset($member['diet']) && $member['diet'] !== '') $this->db->set('diet', $this->shared->prep_option($member['diet'],'text'));
			if (isset($member['shirtsize']) && $member['shirtsize'] !== '') $this->db->set('shirtsize', $this->shared->prep_option($member['shirtsize'],'text'));
			if (isset($member['medical']) && $member['medical'] !== '') $this->db->set('medical', $this->shared->prep_option($member['medical'],'text'));
			if (isset($member['allergies']) && $member['allergies'] !== '') $this->db->set('allergies', $this->shared->prep_option($member['allergies'],'text'));

			// Add to the database
			//print_r($memberdata);die;
			
			$this->db->insert("members");

			// Call the record we just made
			$query = $this->db->get_where('members', array('created' => $timestamp));
			$memberdata = $query->row_array();
			//print_r($query);die;
			if ($this->input->get_post('return')) {
				if (empty($memberdata)) {
					$this->session->set_flashdata('message', 'The member was not added to your unit, an administrator has been notified to look into this issue.');
				} else {
					$this->session->set_flashdata('message', $member['name'].' has been added to your unit, add them to the roster below.');
				}
				redirect($this->input->get_post('return'), 'refresh');
			}
			return $memberdata['id'];

		} else {
			// We are creating lots of users
			if ($details === false) {
				// Get our details from the post vars
				$members = $this->input->post('members');
			} else {
				$members = $details;
			}
			if (!$members) return false;
			$errors['errors'] = array();
			$e = 0;
			$i = 0;

			// Loop
			$membersdata = array();
			foreach ($members as $member) {
				// Start with some error checking
				if (!isset($member['name']) || !isset($member['dob']) || !isset($member['gender'])) {
					$errors['errors'][] = $name.' was not added because you were missing details.';
					$e++;
					continue;
				}

				// Process the details
				$membersdata[] = array(
					'name' 		=> $this->shared->prep_option($member['name'],'text'),
					'address' 	=> (isset($member['address']) && $member['address'] !== '') ? $this->shared->prep_option($member['address'],'text'): null,
					'citystate' => (isset($member['citystate']) && $member['citystate'] !== '') ? $this->shared->prep_option($member['citystate'],'text'): null,
					'insurance' => (isset($member['insurance']) && $member['insurance'] !== '') ? $this->shared->prep_option($member['insurance'],'text'): null,
					'phone' 	=> (isset($member['phone']) && $member['phone'] !== '') ? $this->shared->prep_option($member['phone'],'text'): null,
					'dob' 		=> $this->shared->prep_option($member['dob'],'date'),
					'unit' 		=> $unit,
					'notes' 	=> (isset($member['notes']) && $member['notes'] !== '') ? $this->shared->prep_option($member['notes'],'text'): null,
					'diet' 		=> (isset($member['diet']) && $member['diet'] !== '') ? $this->shared->prep_option($member['diet'],'text'): null,
					'shirtsize' => (isset($member['shirtsize']) && $member['shirtsize'] !== '') ? $this->shared->prep_option($member['shirtsize'],'text'): null,
					'medical' 	=> (isset($member['medical']) && $member['medical'] !== '') ? $this->shared->prep_option($member['medical'],'text'): null,
					'allergies' => (isset($member['allergies']) && $member['allergies'] !== '') ? $this->shared->prep_option($member['allergies'],'text'): null,
					'gender' 	=> $this->shared->prep_option($member['gender'],'text'),
					'created' 	=> $timestamp
				);
				$i++;
			}

			// Batch Insert
			if ($i == 0) {
				return ($e == 0) ? false: $errors;
			} else {
				// We have members to insert
				$this->db->insert_batch('members', $membersdata); 
				return ($e == 0) ? true: $errors;
			}
		}
	}

	/*
	 *
	 *	Activity Functions
	 *	Methods used for the activity views, not to be confused with the event 
	 * 	activity or activity reg views.
	 *
	 */

	/* Get activities
	 * $type = (FALSE) what type or all
	 * $sort = (FALSE|eventtype|category) identify the $type if set, or sort by this
	 * $relational = (FALSE|TRUE) return associative array or no formatting
	 */
	public function get_activities($type=FALSE,$sort=FALSE,$relational=FALSE)
	{
		// Get the activities
		if ($type && $sort) {
			$this->db->where($sort,$type);
			$this->db->order_by($sort.' asc, category asc, title asc');
		}
		if ($sort && !$type) {
			$this->db->order_by($sort.' asc, title asc');
		}
		$query = $this->db->get('activities');
		
		// Return normal or relational array
		if ($relational === false) {
			return $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $a) { $result[$a['id']] = $a;}
			return $result;
		}
	}

	// Get a single activity by id
	public function get_activity($id)
	{
		$query = $this->db->get_where('activities', array('id' => $id));
		return $query->row_array();		
	}

	// Delete a single activity by id
	public function delete_activity($id)
	{
		// Delete activity and roster dependencies
		$tables = array('classes', 'classregs');
		$this->db->where('activity', $id);
		$this->db->delete($tables);

		// Delete our activity
		$this->db->delete('activities', array('id' => $id));
		return true;		
	}

	// Update a single activity by id
	public function update_activity($id, $details=FALSE)
	{
		// Use post or $details for the activity
		if ($details === false) {
			// Get our details from the post vars
			$new = $this->input->post('activity');
		} else {
			// Use our supplied detail array
			$new = $details;
		}
		
		// Get the original
		$activity = $this->get_activity($id);
		if (!isset($activity['id']) || empty($activity['id'])) return false;
		
		// Handle our new details
		if (isset($new['title']) && $activity['title'] !== $new['title']) $activitydata['title'] = $this->shared->prep_option($new['title'],'text');
		if (isset($new['description']) && $activity['description'] !== $new['description']) $activitydata['description'] = $this->shared->prep_option($new['description'],'text');
		if (isset($new['short']) && $activity['short'] !== $new['short']) $activitydata['short'] = (empty($new['short']) && $new['short'] !== '') ? null: $this->shared->prep_option($new['short'],'text');
		if (isset($new['long']) && $activity['long'] !== $new['long']) $activitydata['long'] = (empty($new['long']) && $new['long'] !== '') ? null: $this->shared->prep_option($new['long'],'text');
		if (isset($new['eventtype']) && $activity['eventtype'] !== $new['eventtype']) $activitydata['eventtype'] = $this->shared->prep_option($new['eventtype'],'text');
		if (isset($new['category']) && $activity['category'] !== $new['category']) $activitydata['category'] = $this->shared->prep_option($new['category'],'text');
		$activitydata['meritbadge'] = (isset($new['meritbadge'])) ? $this->shared->prep_option($new['meritbadge'],'checkbox'): 0;
		$activitydata['lastupdate'] = time();
		if (isset($new['age']) && $activity['age'] !== $new['age']) $activitydata['age'] = (empty($new['age']) && $new['age'] !== '') ? 0: $this->shared->prep_option($new['age'],'text');
		
		// Update the activity
		if (isset($activitydata)) {
			$this->db->where('id', $id);
			$this->db->update('activities', $activitydata);
			return true;
		}
		return false;
	}
	
	// Get a single class by id
	public function get_class($id)
	{
		$query = $this->db->get_where('classes', array('id' => $id));
		return $query->row_array();		
	}

	// Get regs for a class
	public function get_class_members($class, $recursive=false)
	{
		$this->db->order_by('time asc');
		$this->db->where('class',$class);
		$query = $this->db->get('classregs');
		$regs = $query->result_array();
		$result = array();
		foreach ($regs as $reg) {
			if ($recursive !== false) $reg['member'] = $this->get_member_details($reg['member']);
			$result[$reg['id']] = $reg;
		}
		return $result;
	}

	// Get a member with recursive details
	public function get_member_details($id)
	{
		// Get our member
		$query = $this->db->get_where('members',array('id'=>$id));
		$member = $query->row_array();
		if (empty($member)) return false;
		// Get their unit as well
		$query = $this->db->get_where('unit',array('id'=>$member['unit']));
		$unit = $query->row_array();
		$member['unit'] = $unit;
		return $member;
	}

	// Get a member's classes with recursive details
	public function get_member_classes($rosterid)
	{
		// Get our class regs
		$this->db->where('roster',$rosterid);
		$query = $this->db->get('classregs');
		$classregs = $query->result_array();

		// Setup
		$query = $this->db->get('activities');
		$activities = array();
		foreach ($query->result_array() as $i) {
			$activities[$i['id']] = $i;
		}
		$query = $this->db->get('classes');
		$classes = array();
		foreach($query->result_array() as $i) {
			$i['activity'] = $activities[$i['activity']];
			$i['blocks'] = unserialize($i['blocks']);
			$classes[$i['id']] = $i;
		}
		
		// Get their unit as well
		$result = array();
		foreach ($classregs as $reg) {
			$reg['class'] = $classes[$reg['class']];
			$result[$reg['id']] = $reg;
		}
		//print_r($result);die;
		return $result;
	}

	// Get the class registrations
	public function get_class_regs($roster,$key=FALSE)
	{
		// Get the activities
		$this->db->where('roster',$roster);
		$query = $this->db->get('classregs');
		
		// Return normal or relational array
		if ($key === false) {
			return $query->result_array();
		} else {
			if ($key === true) $key = 'id';
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $a) { $result[$a[$key]] = $a;}
			return $result;
		}
	}

	// Count the number of open spots in a class for a single session
	public function count_class_openings($session,$class)
	{
		// Get the classes
		$this->db->order_by('time asc');
		$this->db->where('session',$session);
		$this->db->where('class',$class);
		$query = $this->db->get('classregs');
		$classes = $query->result_array();
		$classes = count($classes);
		
		// Get our class
		$this->db->where('id',$class);
		$query = $this->db->get('classes');
		$class = $query->row_array();
		
		// Do math
		$openings = $class['limit'] - $classes;
		if ($class['limit'] == 0 || $class['limit'] == null || $class['limit'] == '') {
			return 'No limit';
		} elseif ($openings > 0) {
			return $openings. ' openings';
		} elseif ($openings == 0) {
			return 'Full, no one waitlisted';
		} elseif (is_numeric($class['hardlimit']) && $class['hardlimit'] > 0 && $classes >= $class['hardlimit']) {
			return "Full, this class has reached it's maximum limit";
		} else {
			return 'Full, '.($openings*(-1)).' on wait list';
		}
	}

	// Count the number of regs in a class for a single session
	public function count_class_regs($session,$class,$number=FALSE)
	{
		// Get the classes
		$this->db->order_by('time asc');
		$this->db->where('session',$session);
		$this->db->where('class',$class);
		$query = $this->db->get('classregs');
		$classes = $query->result_array();
		$classes = count($classes);
		
		// Get our class
		$this->db->where('id',$class);
		$query = $this->db->get('classes');
		$class = $query->row_array();
		
		// Do math
		if ($number === false) {
			$openings = $class['limit'] - $classes;
			if ($class['limit'] == 0 || $class['limit'] == null || $class['limit'] == '') {
				return $classes;
			} elseif ($openings > 0) {
				return $classes;
			} elseif ($openings == 0) {
				return $classes.' (full)';
			} else {
				return $classes.' (full +'.($openings*(-1)).')';
			}
		} else {
			return $classes;
		}
	}

	// Add the new class regs and delete the old.
	public function update_class_regs() 
	{
		// Setup
		$existing = $this->get_class_regs($this->input->post('roster'),'class');
		$classes = $this->input->post('classes');
		$data = array();
		$removals = array();
		
		// Walk the new classes
		if (!empty($classes)) {
			foreach ($classes as $class) {
				if (isset($existing[$class['class']])) {
					unset($existing[$class['class']]);
					continue;
				}
				$data[] = array(
					'member' => $this->input->post('member'),
					'reg' => $this->input->post('reg'),
					'session' => $this->input->post('session'),
					'class' => $class['class'],
					'activity' => $class['activity'],
					'roster' => $this->input->post('roster'),
					'time' => time()
				);
			}
		}

		// Delete the ones not in the new group
		if (!empty($existing)) {
			foreach ($existing as $ex) {
				$this->db->delete('classregs', array('id' => $ex['id']));
			}
		}

		// Insert our new classes
		if (!empty($data)) {
			$this->db->insert_batch('classregs', $data); 
		}
		return true;
	}


	/* Create an activity
	 * $details = (FALSE) use post data 
	 * returns false or id for success
	 */
	public function create_activity($details=FALSE)
	{
		// Setup
		$timestamp = time();

		// Here we go
		if ($details === false) {
    		// Get our details from the post vars
    		$activity = $this->input->post('activity');
    	} else {
    		// Use our supplied detail array
    		$activity = $details;
    	}
    	if (!$activity || !isset($activity['title']) || !isset($activity['description']) || !isset($activity['eventtype']) || !isset($activity['category'])) return $errors['error'] = 'The activity title, description, event type, and category are all required.';

    	// Process the details
    	$activitydata = array(
    		'title' 		=> $this->shared->prep_option($activity['title'],'text'),
    		'description' 	=> $this->shared->prep_option($activity['description'],'text'),
    		'short' 		=> (isset($activity['short']) && $activity['short'] !== '') ? $this->shared->prep_option($activity['short'],'text'): null,
    		'long' 			=> (isset($activity['long']) && $activity['long'] !== '') ? $this->shared->prep_option($activity['long'],'text'): null,
    		'eventtype' 	=> $this->shared->prep_option($activity['eventtype'],'text'),
    		'category' 		=> $this->shared->prep_option($activity['category'],'text'),
    		'age' 			=> (isset($activity['age']) && $activity['age'] !== '') ? $this->shared->prep_option($activity['age'],'text'): null,
    		'meritbadge' 	=> (isset($activity['meritbadge']) && $activity['meritbadge'] !== '') ? $this->shared->prep_option($activity['meritbadge'],'checkbox'): 0,
    		'lastupdate' 	=> $timestamp
    	);

    	// Add to the database
    	$this->db->insert('activities', $activitydata);

    	// Call the record we just made
    	$query = $this->db->get_where('activities', array('lastupdate' => $timestamp, 'title' => $activitydata['title']));
    	$activitydata = $query->row_array();
    	return $activitydata['id'];

	}

	/* Get classes
	 * $type = (FALSE) event id
	 * $relational = (FALSE|TRUE) return associative array or no formatting
	 * in shared
	 */
	public function get_classes($event=FALSE,$relational=FALSE)
	{
		// Get the activities
		if ($event) {
			$this->db->where('event', $event);
		}
		$this->db->order_by('Title','ASC');
		$query = $this->db->get('classes');
		
		// Return normal or relational array
		if ($relational === false) {
			return (empty($query)) ? array(): $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $a) { $result[$a['id']] = $a;}
			return $result;
		}
	}

	// Create a class
	public function create_class($details=FALSE)
	{
		// Setup
		$timestamp = time();

		// Here we go
		if ($details === false) {
    		// Get our details from the post vars
    		$class = $this->input->post('new');
    	} else {
    		// Use our supplied detail array
    		$class = $details;
    	}
    	// Process the details
    	$classdata = array(
    		'title' 		=> $this->shared->prep_option($class['title'],'text'),
    		'location' 		=> $this->shared->prep_option($class['location'],'text'),
    		'open' 			=> (isset($class['open'])) ? $this->shared->prep_option($class['open'],'checkbox'): 0,
    		'limit' 		=> (isset($class['soft']) && $class['soft'] !== '' && $class['soft'] !== '0') ? $this->shared->prep_option($class['soft'],'num'): 0,
    		'hardlimit' 	=> (isset($class['hard']) && $class['hard'] !== '' && $class['hard'] !== '0') ? $this->shared->prep_option($class['hard'],'num'): 0,
    		'preorder' 		=> (isset($class['amount']) && $class['amount'] !== '') ? $this->shared->prep_option($class['amount'],'dollar'): 0,
    		'event' 		=> $class['event'],
    		'activity' 		=> $class['activity'],
    		'blocks'		=> (isset($class['blocks'])) ? serialize($class['blocks']): serialize(array()),
    		'lastupdate' 	=> $timestamp
    	);

    	// Add to the database
    	$this->db->insert('classes', $classdata);

    	// Call the record we just made
    	$query = $this->db->get_where('classes', array('lastupdate' => $timestamp, 'title' => $classdata['title']));
    	$classdata = $query->row_array();
    	return $classdata;

	}

	// Edit a class
	public function edit_class($details=FALSE)
	{
		// Setup
		$timestamp = time();

		// Here we go
		if ($details === false) {
    		// Get our details from the post vars
    		$class = $this->input->post('edit');
    	} else {
    		// Use our supplied detail array
    		$class = $details;
    	}
    	// Process the details
    	$query = $this->db->get_where('classes', array('id' => $class['id']));
    	$classdata = $query->row_array();
    	if (empty($classdata)) return false;
    	$classdata['title'] 		= $this->shared->prep_option($class['title'],'text');
    	$classdata['location'] 		= $this->shared->prep_option($class['location'],'text');
    	$classdata['open'] 			= (isset($class['open'])) ? $this->shared->prep_option($class['open'],'checkbox'): 0;
    	$classdata['limit'] 		= (isset($class['soft']) && $class['soft'] !== '' && $class['soft'] !== '0') ? $this->shared->prep_option($class['soft'],'num'): 0;
    	$classdata['hardlimit'] 	= (isset($class['hard']) && $class['hard'] !== '' && $class['hard'] !== '0') ? $this->shared->prep_option($class['hard'],'num'): 0;
    	$classdata['preorder'] 		= (isset($class['amount']) && $class['amount'] !== '') ? $this->shared->prep_option($class['amount'],'dollar'): 0;
    	$classdata['blocks']		= (isset($class['blocks'])) ? serialize($class['blocks']): serialize(array());
    	$classdata['lastupdate'] 	= $timestamp;

    	// Update the database
		$this->db->where('id', $class['id']);
    	$this->db->update('classes', $classdata);

    	// Call the record we just made
    	$query = $this->db->get_where('classes', array('id' => $class['id']));
    	$classdata = $query->row_array();
    	return $classdata;

	}

	// Delete a class
	public function delete_class($id)
	{
		// Delete activity and roster dependencies
		$tables = array('classregs');
		$this->db->where('class', $id);
		$this->db->delete($tables);

		// Delete our activity
		$this->db->delete('classes', array('id' => $id));
		return true;		
	}

	// Get members
	public function get_roster($unit=FALSE,$reg=FALSE,$relational=FALSE)
	{
		// Sort nicely
		//$this->db->order_by('name asc');

		// Get our unit members if unit is set
		if ($unit === FALSE && $reg !== FALSE) {
			$query = $this->db->get_where('roster', array('reg' => $reg));
		} elseif ($unit === FALSE) {
			$query = $this->db->get('roster');
		} else {
			$query = $this->db->get_where('roster', array('unit' => $unit, 'reg' => $reg));
		}

		// Return normal or relational array
		if ($relational === false) {
			return $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $u) { $result[$u['id']] = $u;}
			return $result;
		}
	}

	// Get one person on a roster
	public function get_single_roster($rosterid, $recursive=FALSE)
	{
		// Get our unit members if unit is set
		$query = $this->db->get_where('roster', array('id' => $rosterid));
		$roster = $query->row_array();
		
		if ($recursive === false) $roster['member'] = $this->get_member_details($roster['member']);
		return $roster;
	}

	// Get class position
	public function class_position($roster, $class, $session, $num=FALSE)
	{
		// Get the students
		$this->db->order_by('time asc');
		$query = $this->db->get_where('classregs', array('class' => $class, 'session' => $session));
		$students = $query->result_array();

		// Get the class
		$query = $this->db->get_where('classes', array('id' => $class));
		$class = $query->row_array();

		$i = 1;
		foreach ($students as $s) {
			if ($s['roster'] == $roster) {
				if ($class['limit'] == 0 || $i <= $class['limit']) {
					return 'Registered';
				} elseif ($i > $class['hardlimit']) {
					$spot = $i - $class['limit'];
					return 'Waitlisted, #'.$spot.' (HL)';
				} elseif ($i > $class['limit']) {
					$spot = $i - $class['limit'];
					return 'Waitlisted, #'.$spot;
				} else {
					return 'Not Registered...';
				}
			}
			$i++;
		}
	}

	// Delete a class
	public function delete_roster($roster, $member)
	{
		// Delete activity and roster dependencies
		$tables = array('classregs');
		$this->db->where('member', $member);
		$this->db->delete($tables);

		// Delete our activity
		$this->db->delete('roster', array('id' => $roster));
		return true;		
	}

	// Create a class
	public function create_roster($reg, $unit, $youth=FALSE, $adults=FALSE)
	{
		// Setup
		$timestamp = time();

		// Here we go
		if ($youth === false) {
    		// Get our details from the post vars
    		$youth = $this->input->post('youth');
    	} 
		if ($adults === false) {
    		// Get our details from the post vars
    		$adults = $this->input->post('adults');
    	} 

    	// Process the details
    	$roster = array();
    	if ($adults) : foreach ($adults as $a) :
    		if ($a['id'] == '0') continue;
	    	$roster[] = array(
	    		'reg' => $reg,
	    		'unit' => $unit,
	    		'member' => $a['id'],
	    		'notes' => '',
	    		'timestamp' => $timestamp
	    	);
    	endforeach; endif;
    	if ($youth) : foreach ($youth as $y) :
    		if ($y['id'] == '0') continue;
	    	$roster[] = array(
	    		'reg' => $reg,
	    		'unit' => $unit,
	    		'member' => $y['id'],
	    		'notes' => '',
	    		'timestamp' => $timestamp
	    	);
    	endforeach; endif;

    	if (!empty($roster)) { 
    	// Add to the database
		$this->db->insert_batch('roster', $roster); 
		
		// Update reg with roster
		$this->db->where('id', $reg);
    	$this->db->update('eventregs', array('roster'=>1));
    	}
		
    	return true;

	}
}

?>