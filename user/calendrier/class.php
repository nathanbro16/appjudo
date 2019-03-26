<?php

/**
 *
 */
class Month
{
	public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
	private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'octobre', 'Novembre', 'Décembre'];
	public $month;
	public $year;
	public $day;
	public $start;
	public $end;

	/**
	month =  1 - 12
	$year = l'année / year
	**/

	function __construct($month = null, $year = null, $day = null, $start = null, $end = null)
	{

		if ($month === null) {
			$month = intval(date('m'));
		}
		if ($year === null) {
			$year = intval(date('Y'));
		}
		if ($day != null) {
			if ($day >= 1 && $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
				$this->day = $day;
			}elseif ($day > cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
				throw new Exception("le jour n'exsite pas.");
			}
		}
		if ($month > 12) {
			if ($month !== 12) {
				$month = ($month % 12);
			}

		}
		if ($start != null) {
			$this->start = $start;
		}
		if ($end != null) {
			$this->end = $end;
		}

		if ($year < 2017) {
			throw new Exception("l'année doit être supérieur a 2017.");
		}
		$this->month = $month;
		$this->year = $year;
	}

	public function getstartingDay ()
	{
		return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
	}
	public function toString(){
		return $this->months[$this->month - 1] . ' ' . $this->year;
	}
	public function toStringday(){
		$day = (new \DateTimeImmutable("{$this->year}-{$this->month}-{$this->day}"))->format('N')-1;
		return $this->days[$day]. ' ' .$this->day.' '.$this->months[$this->month - 1] . ' ' . $this->year;
	}
	public function getWeeks(){
		$start = $this->getstartingDay();
		$end = (clone $start)->modify('+1 month -1 day');
		$startweek = intval($start->format('W'));
		$endweek = intval($end->format('W'));
		if ($endweek === 1) {
			$endweek = intval((clone $end)->modify('- 7days')->format('W'))+1;
		}
		$weeks = $endweek - $startweek + 1;
		if ($weeks < 0) {
			$weeks = intval($end->format('W'));
		}
		return $weeks;

	}
	public function withinMonth(\DateTimeImmutable $date): bool	{
		return $this->getstartingDay()->format('Y-m') === $date->format('Y-m');

	}
	public function nextMonth(): month{
		$month = $this->month +1 ;
		$year = $this->year;
		if ($month > 12) {
			$month = 1;
			$year += 1;
		}
		return new Month($month, $year);
	}
	public function previousMonth(): month{
		$month = $this->month -1 ;
		$year = $this->year;
		if ($month < 1) {
			$month = 12;
			$year -= 1;
		}
		return new Month($month, $year);
	}
	public function getDay()
	{
		return new \DateTimeImmutable("{$this->year}-{$this->month}-{$this->day}");
	}
	public function wathdayname($date):string
	{
		if (date('Y-m-d') === $this->getDay()->format('Y-m-d')) {
			$wathdayname = "Aujourd'hui";
		} elseif (date('Y-m-d',strtotime("+ 1 days")) === $this->getDay()->format('Y-m-d')) {
			$wathdayname = "Demain";
		} else {
			$wathdayname = $date;
		}
		return $wathdayname;
	}
	public function getstart()
	{
		return new \DateTimeImmutable("{$this->start}");
	}
	public function getend()
	{
		return new \DateTimeImmutable("{$this->end}");
	}
}
/**
 *
 */
class Event
{
	private $id;
	private $eventname;
	private $description;
	private $start;
	private $end;
	private $inscription;

	public function getid(): int{
		return $this->id;
	}
	public function getname(): string{
		return $this->eventname;
	}
	public function getdescription(): string{
		return $this->description;
	}
	public function getstart(): DateTimeImmutable {
		return (new DateTimeImmutable($this->start));
	}
	public function getend(): DateTimeImmutable{
		return (new DateTimeImmutable($this->end));
	}
	public function getinscp()
	{
		return $this->inscription;
	}
	public function setName(string $name)
	{
		$this->eventname = $name;
	}
	public function setDescription(string $description)
	{
		$this->description = $description;
	}
	public function setStart(string $start)
	{
		$this->start = $start;
	}
	public function setEnd(string $end)
	{
		$this->end = $end;
	}
	public function setinscp(string $inscrip)
	{
		$this->inscription = $inscrip;
	}
}
/**
 *
 */
class Events
{

	private $bdd;

	public function __construct(\PDO $bdd){
		$this->bdd = $bdd;
	}

	public function getEeventsBetween(\DateTimeImmutable $start, \DateTimeImmutable $end): array
	{
		$sql = "SELECT * FROM events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ";
		$statement = $this->bdd->query($sql);
		$resultat = $statement->fetchAll();
		return $resultat;
	}
	public function getEeventsBetweenByDay(\DateTimeImmutable $start, \DateTimeImmutable $end): array
	{
		$events = $this->getEeventsBetween($start, $end);
		$days = [];
		foreach ($events as $events) {
			$date = explode(' ', $events['start'])[0];
			if (!isset($days[$date])) {
				$days[$date] = [$events];
			}
			else{
				$days[$date][] = $events;
			}
		}
		return $days;
	}
	public function find(int $id): Event
	{
		$statement = $this->bdd->query("SELECT * FROM events WHERE id = $id LIMIT 1");
		$statement->setFetchMode(\PDO::FETCH_CLASS, Event::class);
		$result = $statement->fetch();
		if ($result === false) {
			throw new Exception('Aucun résultat n\'a été trouvé');
		}
		return $result;
	}
	public function create(Event $event) : bool
	{
		$statement = $this->bdd->prepare('INSERT INTO events (eventname, description, start, end, inscription) VALUES (?, ?, ?, ?, ?)');
		return $statement->execute([
			$event->getname(),
			$event->getdescription(),
			$event->getstart()->format('Y-m-d H:i:s'),
			$event->getend()->format('Y-m-d H:i:s'),
			($event->getinscp()-1),
		]);
	}
	public function update(Event $event) : bool
	{
		$statement = $this->bdd->prepare('UPDATE events SET eventname = ?, description = ?, start = ?, end = ?, inscription = ? WHERE id = ?');
		return $statement->execute([
			$event->getname(),
			$event->getdescription(),
			$event->getstart()->format('Y-m-d H:i:s'),
			$event->getend()->format('Y-m-d H:i:s'),
			($event->getinscp()-1),
			$event->getid(),
		]);
	}
	public function hydrate(Event $event, array $data)
	{
	    $event->setName($data['eventname']);
	    $event->setDescription($data['description']);
	    $event->setStart(DateTimeImmutable::createFromFormat('Y-m-d H:i', $data['date'] . '' . $data['start'])->format('Y-m-d H:i:s'));
	    $event->setEnd(DateTimeImmutable::createFromFormat('Y-m-d H:i', $data['date'] . '' . $data['end'])->format('Y-m-d H:i:s'));
	    $event->setinscp($data['inscript']);
	    return $event;
	}
	public function delete(Event $event)
	{
		$statement = $this->bdd->prepare('DELETE FROM events WHERE id = ?');
		return $statement->execute([
			$event->getid(),
		]);
	}

}
/**
 *
 */
class Validator
{
	private $data;
	public $errors = [];
	public $nameerror = [];

	protected function validates(array $data){
		$this->errors = [];
		$this->data = $data;

	}
	public function validate(string $field, string $method, ...$parameters)
	{
		$this->nameerror[] = $field;
		if (empty($this->data[$field])) {
			$this->errors[$field] = "le champs $field n\'est pas remplis";

		}else{
			call_user_func([$this, $method], $field, ...$parameters);
		}
	}
	public function minLength(string $field, int $lenght):bool
	{
		if (mb_strlen($this->data[$field]) < $lenght) {
			$this->errors[$field] = "le champs dois avoir plus de $lenght caractère";
			return false;
		}
		return true;

	}
	public function date(string $field):bool
	{
		if(\DateTimeImmutable::createFromFormat('Y-m-d', $this->data[$field]) === false){
			$this->errors[$field] = "la date ne semble pas valide";
			return false;
		}
		return true;

	}
	public function time(string $field):bool
	{
		if(\DateTimeImmutable::createFromFormat('H:i', $this->data[$field]) === false){
			$this->errors[$field] = "le temp ne semble pas valide";
			return false;
		}
		return true;

	}
	public function beforeTime(string $startField, string $endField)
	{
		if ($this->time($startField) && $this->time($endField)) {
			$start = \DateTimeImmutable::createFromFormat('H:i', $this->data[$startField]);
			$end = \DateTimeImmutable::createFromFormat('H:i', $this->data[$endField]);
			if ($start->getTimestamp() > $end->getTimestamp()) {
				$this->errors[$startField] = "Le temps doit être inférieur au temp de fin";
				return false;
			}
			return true;
		}
		return false;
	}
	public function inscripevent(string $filed)
	{
		if ($this->data[$filed] >= '4') {
			$this->errors[$filed] = "Merci de respecter les valleurs préremplis";
			$this->data[$filed] = $this->data[$filed]-1;
			return false;
		}
		return true;
	}



}
/**
 * @param array $data
 * @return array / bool
 *
 */
class EventValidator extends Validator
{
	public function validates(array $data){
		parent::validates($data);
		$this->validate('eventname', 'minLength', 8);
		$this->validate('date', 'date');
		$this->validate('start', 'beforeTime', 'end');
		$this->validate('inscript', 'inscripevent');
		return $this->errors;
	}

}
