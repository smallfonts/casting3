<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class HungarianAlgorithmUtil {

    private $matrix = array();
    private $rows = 0;
    private $cols = 0;
    //the dimension of the matrix, which should be equals to the row and col since matrix is a sqare
    private $dimension = 0;
    private $labelBySlots;
    private $labelByInterviewees;
    private $minSlackIntervieweeBySlot;
    private $minSlackValueBySlot;
    private $assignedSlotForInterviewee;
    private $assignedIntervieweeForSlot;
    private $parentIntervieweeByCommittedSlot;
    private $committedInterviewees;

    //Assumption made in this function
    //1. matrix is square (rows = columns)
    //2. dummy rows/cols are padded with -1
    public function process($matrix) {
        $this->dimension = max(count($matrix), count($matrix[0]));
        $this->rows = count($matrix);
        $this->cols = count($matrix[0]);
        $this->matrix = $matrix;

        //set default assignment to -1 for both row and column
        $this->assignedSlotForInterviewee = array_fill(0, $this->dimension, -1);
        $this->assignedIntervieweeForSlot = array_fill(0, $this->dimension, -1);

        $this->labelBySlots = array_fill(0, $this->dimension, 0);
        $this->labelByInterviewees = array_fill(0, $this->dimension, 0);
        return $this->run();
    }

    //returns the selected priority of interviewees to slots
    //if value is -1, it means that an interviewee/slot has no assignment
    private function run() {
        $this->minimize();
        //$this->findlabelBySlots();
        $this->assignSlots();

        $unassignedInterviewee = $this->getUnassignedInterviewee();
        
        //keep running as long as there is an unassigned slot
        while ($unassignedInterviewee != -1) {
            $this->initIteration($unassignedInterviewee);
            $this->executeIteration();
            $unassignedInterviewee = $this->getUnassignedInterviewee();
        }

        $result = $this->assignedSlotForInterviewee;
        for ($r = 0; $r < count($result); $r++) {
            if ($result[$r] >= $this->cols) {
                $result[$r] = -1;
            }
        }
        
        return $result;
    }
    
    
    private function initIteration($r) {
        //reset committedInterviewees and set $r as a committed interviewee
        $this->committedInterviewees = array_fill(0, $this->dimension, false);
        $this->committedInterviewees[$r] = true;
        
        //reset parent interviewee by committedslot
        $this->parentIntervieweeByCommittedSlot = array_fill(0, $this->dimension, -1);
        
        for ($c = 0; $c < $this->dimension; $c++) {
            $this->minSlackValueBySlot[$c] = $this->matrix[$r][$c] - $this->labelByInterviewees[$r] - $this->labelBySlots[$c];
            echo $this->matrix[$r][$c]." - ".$this->labelByInterviewees[$r]." - ".$this->labelBySlots[$c];
            //defines the interviewee for each slot, which minSlackValueBySlot is derived from
            $this->minSlackIntervieweeBySlot[$c] = $r;
        }
        
        print_r($this->minSlackValueBySlot);
    }

    private function executeIteration() {
        while (true) {
            $minSlackInterviewee = -1;
            $minSlackSlot = -1;
            $minSlackValue = -3;
            
            //find the minimum slack value (priority - labelbyslot - labelbyinterviewee) 
            //in minSlackValue
            //
            for ($c = 0; $c < count($this->minSlackValueBySlot); $c++) {
                if ($this->parentIntervieweeByCommittedSlot[$c] == -1) {
                    echo "$minSlackValue vs ".$this->minSlackValueBySlot[$c]." <br/>";
                    
                    if ($minSlackValue == -3 || $this->minSlackValueBySlot[$c] < $minSlackValue) {
                        $minSlackValue = $this->minSlackValueBySlot[$c];
                        
                        $minSlackInterviewee = $this->minSlackIntervieweeBySlot[$c];
                        $minSlackSlot = $c;
                    }
                }
            }
            
            if ($minSlackValue > 0) {
                $this->updateLabels($minSlackValue);
            }
            
            //identifies the the intervieewee of a committed slot
            $this->parentIntervieweeByCommittedSlot[$minSlackSlot] = $minSlackInterviewee;
            
            //checks if min slack slot has an assigned interviewee
            //
            $interviewee = $this->assignedIntervieweeForSlot[$minSlackSlot];
            if ($interviewee == -1) {
                
                //A new assignment for the slot can be made!
                $assignedSlot = $minSlackSlot;
                
                //gets the interviewee to assign the slot to
                $parentInterviewee = $this->parentIntervieweeByCommittedSlot[$assignedSlot];
                while (true) {
                    
                    //previous assignment for interviewee
                    $temp = $this->assignedSlotForInterviewee[$parentInterviewee];
                    
                    //assign the interviewee to the slot
                    $this->assign($parentInterviewee, $assignedSlot);
                    
                    //previously assigned slot
                    $assignedSlot = $temp;
                    
                    //if interviewee has no previously assigned slot, then exit
                    if ($assignedSlot == -1) {
                        break;
                    }
                    
                    //if a slot has been previously assigned to an interviewee, then get the interviewee
                    //that has been committed to that slot.
                    $parentInterviewee = $this->parentIntervieweeByCommittedSlot[$assignedSlot];
                }
                return;
            } else {
                
                //min slack slot has been assigned to an interviewee
                //
                
                /*
                 * Update slack values since we increased the size of the
                 * committed workers set.
                 */
                
                $this->committedInterviewees[$interviewee] = true;
                for ($c = 0; $c < $this->dimension; $c++) {
                    if ($this->parentIntervieweeByCommittedSlot[$c] == -1) {
                        $slack = $this->matrix[$interviewee][$c] - $this->labelByInterviewees[$interviewee] - $this->labelBySlots[$c];
                        if ($this->minSlackValueBySlot[$c] > $slack) {
                            $this->minSlackValueBySlot[$c] = $slack;
                            $this->minSlackIntervieweeBySlot[$c] = $interviewee;
                        }
                    }
                }
            }
        }
    }
    
    
    private function updateLabels($minSlackValue) {
        echo "min slack value :".$minSlackValue;
        echo "<br/>";
        for ($r = 0; $r < $this->dimension; $r++) {
            if ($this->committedInterviewees[$r]) {
                $this->labelByInterviewees[$r] += $minSlackValue;
            }
        }
        for ($r = 0; $r < $this->dimension; $r++) {
            if ($this->parentIntervieweeByCommittedSlot[$r] != -1) {
                $this->labelBySlots[$r] -= $minSlackValue;
            } else {
                $this->minSlackValueBySlot[$r] -= $minSlackValue;
            }
        }
    }


    //returns the first occurance of an unassigned interviewee
    //returns -1 if all interviewees have been assigned
    private function getUnassignedInterviewee() {
        for ($r = 0; $r < $this->dimension; $r++) {
            if ($this->assignedSlotForInterviewee[$r] == -1) {
                return $r;
            }
        }
        return -1;
    }

    private function assignSlots() {
        /* for ($r = 0; $r < $this->dimension; $r++) {
          for ($c = 0; $c < $this->dimension; $c++) {
          //check that no assignment has been made for interviewee and slot
          if ($this->assignedSlotForInterviewee[$r] == -1 && $this->assignedIntervieweeForSlot[$c] == -1) {
          if ($this->matrix[$r][$c] - $this->labelByInterviewees[$r] - $this->labelBySlots[$c] == 0) {
          $this->assign($r, $c);
          }
          }
          }
          } */
        for ($r = 0; $r < $this->dimension; $r++) {
            for ($c = 0; $c < $this->dimension; $c++) {
                //check that no assignment has been made for interviewee and slot
                if ($this->assignedSlotForInterviewee[$r] == -1 && $this->assignedIntervieweeForSlot[$c] == -1) {
                    if ($this->matrix[$r][$c] == 0) {
                        $this->assign($r, $c);
                    }
                }
            }
        }
    }

    private function assign($intervieweeIndex, $slotIndex) {
        echo "Assigning $intervieweeIndex with $slotIndex <br/>";
        $this->assignedSlotForInterviewee[$intervieweeIndex] = $slotIndex;
        $this->assignedIntervieweeForSlot[$slotIndex] = $intervieweeIndex;
    }

    /* identify the minimum value for each column
      private function findlabelBySlots() {
      $this->labelBySlots = array_fill(0,$this->dimension,-3);
      for ($r = 0; $r < $this->dimension; $r++) {
      for ($c = 0; $c < $this->dimension; $c++) {
      if ($this->labelBySlots[$c] == -3 || $this->matrix[$r][$c] < $this->labelBySlots[$c]) {
      $this->labelBySlots[$c] = $this->matrix[$r][$c];
      }
      }
      }
      print_r($this->labelBySlots);
      } */

    //subtract each element of the row with the smallest number of that row
    //subtract each element of the column with the smallest number of that column
    private function minimize() {
        for ($r = 0; $r < $this->dimension; $r++) {
            //find minimum value
            $min = -3;
            for ($c = 0; $c < $this->dimension; $c++) {
                if ($min == -3 || $this->matrix[$r][$c] < $min) {
                    $min = $this->matrix[$r][$c];
                }
            }
            //delete each row by its minimum value
            for ($c = 0; $c < $this->dimension; $c++) {
                $this->matrix[$r][$c] -= $min;
            }
        }

        //find the minimum values for each column
        $columnMin = array();
        for ($r = 0; $r < $this->dimension; $r++) {
            for ($c = 0; $c < $this->dimension; $c++) {
                if (!isset($columnMin[$c]) || $this->matrix[$r][$c] < $columnMin[$c]) {
                    $columnMin[$c] = $this->matrix[$r][$c];
                }
            }
        }

        //deduct each element with their respective minimum values
        for ($r = 0; $r < $this->dimension; $r++) {
            for ($c = 0; $c < $this->dimension; $c++) {
                if (!isset($columnMin[$c]) || $this->matrix[$r][$c] < $columnMin[$c]) {
                    $this->matrix[$r][$c] -= $this->matrix[$r][$c] - $columnMin[$c];
                }
            }
        }
    }

}

?>
