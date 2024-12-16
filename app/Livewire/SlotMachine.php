<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SlotMachine extends Component
{  
    const PROB60 = 0.6;
    const PROB50 = 0.5;
    const PROB40 = 0.4;
    const PROB30 = 0.3;

    // wrong way round (oops!)
    public $cashOutCoords = ['x' => 0, 'y' => 0];

    public $firstLoad = true;
    public $cashOutDisabled = false;
    public $credit = 0;
    public $totalCredit = 0;

    public $numberOfReels = 3;
    public $reels = [];
    public $spinDelay = 1000;
    public $respun = false;

    public $spinSymbol = 'X';
    
    private $symbols = ['C', 'L', 'O', 'W'];
    private $prizes = [10, 20, 30, 40]; 

    protected $currentUser;
    
    public function __construct()
    {
        // Initialize properties or perform other initialization logic
        $this->currentUser = Auth::user();
    }

    /**
     * set starting values after loading component
     * 
     * @return void
     */
    public function mount()
    {        
        $this->reels = $this->spinReels();
        $this->totalCredit = $this->currentUser->credits;
    }

    /**
     * render the blade
     * 
     * @return void
     */
    public function render()
    {
        return view('livewire.slot-machine');
    }

    /**
     * Spin the machine
     * @param bool $debug
     * @return void
     */
    public function spin(bool $debug = false)
    {
        if (0 >= $this->credit) {
            return $this->cashOut();
        }
                
        $this->firstLoad = false;
        $this->credit--;

        // spin
        $this->respun = false;
        $this->dispatch('animateReels');
        $this->reels = $this->spinReels();

        // check result
        $creditsWon = $this->checkReelForMatches();

        // if a winning roll
        if ($creditsWon || $debug) {

            $randomNumber = mt_rand() / mt_getrandmax();

            // over 60 credits, reroll 60 percent of the time
            $isOver60 = 60 < $this->credit && $randomNumber < self::PROB60;

            // 40 - 60 credits, reroll 30 percent of the time
            $isOver40 = 40 <= $this->credit && $randomNumber < self::PROB30;

            if ($isOver60 || $isOver40) {
                $this->respun = true;
                $this->reels = $this->spinReels();
            } 
            
            // recalculate prize
            $creditsWon = $this->checkReelForMatches();
        }

        // delay so as to not update credits before animation
        usleep($this->spinDelay * $this->numberOfReels);
    
        // payout
        $this->credit += $creditsWon;
    }

    /**
     * return an array of random symbols to simulate the starting spinner result
     * 
     * @return array
     */
    private function spinReels()
    {        
        return ["W", "W", "W"];
        $randomArray = [];

        for ($i = 0; $i < $this->numberOfReels; $i++) {
            $randomIndex = array_rand($this->symbols);
            $randomArray[$i] = $this->symbols[$randomIndex];
        }

        return $randomArray;
    }

    /**
     * check reel for matches and return credits won
     * 
     * @return int
     */
    private function checkReelForMatches()
    {
        $firstReel = $this->reels[0]; // Get the first element of the array

        for ($i = 1; $i < $this->numberOfReels; $i++) {
            if ($this->reels[$i] !== $firstReel) {
                return 0;
            }
        }

        return $this->getMatchValue($firstReel);
    }

    /**
     * get fruit row winning value
     * 
     * @param char $match
     * @return int
     */
    private function getMatchValue($match) 
    {
        foreach ($this->symbols as $index => $symbol) {
            if ($symbol === $match) {
                return $this->prizes[$index];
            }
        }

        return 0;
    }

    /**
     * Save winnings and log user out
     * 
     * @param bool $debug
     * @return mixed
     */
    public function cashOut($debug = false)
    {   
        if (!$debug) {

            $randomNumber = mt_rand() / mt_getrandmax();

            // 50% chance to move button
            if ($randomNumber < self::PROB50) {
                return $this->moveCashOutButton();
            } 
    
            // 40% chance to disable button
            if ($randomNumber < self::PROB40) {
                $this->cashOutDisabled = true;
                return false;
            }
        }       

        // otherwise save winnings and log out as usual
        $this->currentUser->credits += $this->credit;
        $this->currentUser->save();
        
        Auth::logout();
        return redirect()->route('login');
    }

    private function moveCashOutButton() {

        // Calculate random angle in radians
        $distance = 300;
        $angle = deg2rad(rand(0, 360));

        // Calculate new coordinates based on angle and distance (300 units)
        $newX = $this->cashOutCoords['x'] + $distance * cos($angle);
        $newY = $this->cashOutCoords['y'] + $distance * sin($angle);

        $this->cashOutCoords['x'] = $newX;
        $this->cashOutCoords['y'] = $newY;
    }
}
