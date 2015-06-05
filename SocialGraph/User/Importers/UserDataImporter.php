<?php namespace SocialGraph\User\Importers;

/**
 * Written by Jeremy Payne
 */

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use SocialGraph\User\Models\User;
use SocialGraph\User\Models\Relationship;


use Exception;

class UserDataImporter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import de data from the json file.';

	/**
	 * @var Filesystem
	 */
	protected $file;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var Relationship
	 */
	protected $relationship;

	/**
	 * Create a new command instance
	 *
	 * @param Filesystem $filesystem
	 * @param User $user
	 * @param Relationship $relationship
	 */
	public function __construct(Filesystem $filesystem, User $user, Relationship $relationship)
	{
		$this->file = $filesystem;
		$this->user = $user;
		$this->relationship = $relationship;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Check if the file exists and is of proper format
		$users = $this->loadData();

		// Empty the previous contents of the DB
		$this->emptyPreviousData();

		// Parse each user to import them
		foreach($users as $user) {
			$this->importUser($user);
		}

		// Parse each user to import friends
		foreach($users as $user) {
			$this->importFriends($user);
		}

		$this->info('Finished importing User data.');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
		];
	}

	/**
	 * Get the data from the file
	 * @return mixed
	 * @throws Exception
	 */
	protected function loadData()
	{
		$file = public_path('data.json');
		if($this->file->exists($file) === false) {
			throw new Exception('Please make sure the data.json file is located in the public/ directory.');
		}
		elseif($this->file->mimeType($file) != "text/plain") {
			throw new Exception('Please make sure the data.json file is of text/plain type.');
		}

		try {
			$content = $this->file->get($file);
			return json_decode($content);
		}
		catch(Exception $e) {
			throw new Exception('Please make sure the data.json file is of json format.');
		}

	}

	/**
	 * Remove data from the DB to start fresh
	 */
	protected function emptyPreviousData()
	{
		// Delete all relationships
		$this->relationship->truncate();

		// Delete all the users
		$this->user->truncate();

		$this->info("Truncated tables");
	}

	/**
	 * Import a JSON user
	 * @param $user
	 */
	protected function importUser($user)
	{
		$data = [
			'id' => $user->id,
			'firstname' => $user->firstName,
			'surname' => $user->surname,
			'age' => $user->age,
			'gender' => $user->gender
		];

		$newUser = $this->user->newInstance();
		$newUser->fill($data);
		$newUser->save();

		$this->info("Imported user {$newUser->id}.");

		unset($newUser);
	}

	/**
	 * Import relations
	 * @param $user
	 */
	protected function importFriends($user)
	{
		foreach($user->friends as $friend) {
			$relation = $this->relationship->newInstance([
				'user_id' => $user->id,
				'relation_id' => $friend
			]);
			$relation->save();

			unset($relation);
		}
	}

}
