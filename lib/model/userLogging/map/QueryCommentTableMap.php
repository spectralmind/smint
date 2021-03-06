<?php


/**
 * This class defines the structure of the 'smint_querycomment' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Wed May 30 15:27:46 2012
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.userLogging.map
 */
class QueryCommentTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.userLogging.map.QueryCommentTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('smint_querycomment');
		$this->setPhpName('QueryComment');
		$this->setClassname('QueryComment');
		$this->setPackage('lib.model.userLogging');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('smint_querycomment_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('SMINT_USER_ID', 'SmintUserId', 'INTEGER', 'smint_user', 'ID', false, null, null);
		$this->addColumn('QUERYTRACKID', 'Querytrackid', 'INTEGER', false, null, null);
		$this->addColumn('COMMENT', 'Comment', 'LONGVARCHAR', false, null, null);
		$this->addColumn('RATING', 'Rating', 'FLOAT', false, null, null);
		$this->addColumn('FEATUREVECTORTYPEID', 'Featurevectortypeid', 'INTEGER', false, null, null);
		$this->addColumn('DISTANCETYPEID', 'Distancetypeid', 'INTEGER', false, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('SmintUser', 'SmintUser', RelationMap::MANY_TO_ONE, array('smint_user_id' => 'id', ), null, null);
    $this->addRelation('QueryCommentTrack', 'QueryCommentTrack', RelationMap::ONE_TO_MANY, array('id' => 'smint_querycomment_id', ), null, null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
			'symfony_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
		);
	} // getBehaviors()

} // QueryCommentTableMap
