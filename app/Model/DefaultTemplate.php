<?php
class DefaultTemplate extends AppModel{
	var $name = 'DefaultTemplate';
	
	function getDefaultTemplate($condition = array(), $fields = array()) {
	    $this->recursive = -1;
	    return $this->find('first',array('conditions'=>$condition,'fields'=>$fields));
	}
	function store_default_template(){
		$defaultTmpls = array(
			array(
			'id' => '1',
			'name' => 'Meeting Minute',
			'description' => '<b>Attendees:</b>  John, Michael<br/>
				<b>Date and Time:</b> July 11th 11 am PST<br/>
				<b>Purpose:</b><br/>
				
				<br/>
				<b>Agenda:</b> 
				<o>
					<li>Discuss Layout </li>
					<li>Discuss on Design</li>
				</ol>
				<br/>
				<b>Discussion:</b><br/>',
			),
			array(
			'id' => '2',
			'name' => 'Status update',
			'description' => '<p><strong>Today\'s accomplishment:</strong></p>
				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>
				<ul>
				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>
				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>
				</ul>
				<p>&nbsp; &nbsp; &nbsp;<strong>Task no: 125</strong></p>
				<ul>
				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>
				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>
				</ul>
				<p><br /> <strong>List of files changed:</strong></p>
				<ol>
				<li>index.html</li>
				<li>style.css</li>
				<li>contact-us.html</li>
				</ol>
				<p>Is code checked in Repository: <strong>Y/N</strong><br /> Is code available in Stager: <strong>Y/N</strong> </p>
				<p><strong>Blocker/Testing Issues:</strong></p>
				<p><strong>Milestone Update: &lt; Specify Milestone name here &gt;</strong></p>
				<p>&nbsp; &nbsp;1. Total tasks:</p>
				<p>&nbsp; &nbsp;2. # of Work in Progress tasks:</p>
				<p>&nbsp; &nbsp;3. # of Resolve tasks:</p>
				<p>&nbsp; &nbsp;4. # of tasks not started:</p>
				<p><br /> <strong>Next Day\'s Plan:</strong></p>',
			),
			array(
			'id' => '3',
			'name' => 'Change Request',
			'description' => '<p><strong>Change Requested:</strong></p>
				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>
				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 125</strong></p>
				<p><strong>Today\'s accomplishment:</strong></p>
				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>
				<ul>
				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>
				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>
				</ul>
				<p>&nbsp; &nbsp; &nbsp;<strong>Task no: 125</strong></p>
				<ul>
				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>
				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>
				</ul>
				<p><br /> <strong>List of files changed:</strong></p>
				<ol>
				<li>index.html</li>
				<li>style.css</li>
				<li>contact-us.html</li>
				</ol>
				<p>Is code checked in Repository: <strong>Y/N</strong><br /> Is code available in Stager: <strong>Y/N</strong> </p>
				<p><strong>Blocker/Testing Issues:</strong></p>
				<p><strong>Milestone Update: &lt; Specify Milestone name here &gt;</strong></p>
				<p>&nbsp; &nbsp;1. Total tasks:</p>
				<p>&nbsp; &nbsp;2. # of Work in Progress tasks:</p>
				<p>&nbsp; &nbsp;3. # of Resolve tasks:</p>
				<p>&nbsp; &nbsp;4. # of tasks not started:</p>
				<p><br /> <strong>Next Day\'s Plan:</strong></p>',
			),
			array(
			'id' => '4',
			'name' => 'Bug',
			'description' => '<b>Browser version:</b>
				<br/>
				<b>OS version:</b>
				<br/><br/>
				<b>Url:</b>
				<br/><br/>
				<b>What is the test case:</b><br/>
				<b>What is the expected result:</b><br/>
				<b>What is the actual result:</b><br/><br/>
				
				<b>Is it happening all the time or intermittently:</b><br/>
				<br/>
				<b>Attach screenshots:</b>'
			)
		);
		$this->query('TRUNCATE table default_templates;');
		$this->SaveAll($defaultTmpls);
		$this->query('DELETE FROM case_templates WHERE user_id=0;');
		return;
	}
	function store_default_to_cstmpl($all_company){
		$dflt_tmpls = $this->find('all', array('fields'=>array('name', 'description')));
		
		$caseTemplates = array();
		foreach($all_company as $cm){
			$caseTemplate = array();
			foreach($dflt_tmpls as $dflt_tmpl){
				$caseTemplate['CaseTemplate'] = $dflt_tmpl['DefaultTemplate'];
				$caseTemplate['CaseTemplate']['company_id'] = $cm;
				$caseTemplate['CaseTemplate']['user_id'] = 0;
				$caseTemplates[] = $caseTemplate;
			} //pr($caseTemplates);die;
		}
		App::import('Model','CaseTemplate');
		$caseTemplateModel = new CaseTemplate(); 
		$caseTemplateModel->saveAll($caseTemplates);
	}
}
?>