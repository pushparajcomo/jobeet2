<?php

namespace Application\ADesigns\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ADesigns\CalendarBundle\Event\CalendarEvent;

class CalendarController extends Controller
{
    /**
     * Dispatch a CalendarEvent and return a JSON Response of any events returned.
     * 
     * @param Request $request
     * @return Response
     */
    public function loadCalendarAction(Request $request)
    {
        
//        echo "Hi"; exit;
        
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('end'));
        
        $events = $this->container->get('event_dispatcher')->dispatch(CalendarEvent::CONFIGURE, new CalendarEvent($startDatetime, $endDatetime))->getEvents();
        $em = $this->getDoctrine()->getEntityManager();
        
         $room_id_qry = "SELECT id FROM AccommodationRoom WHERE accommodation_id =".$request->get('accommodation_id');
            $room_id_stmt = $em->getConnection()->prepare($room_id_qry);
            $room_id_stmt->execute();
            $room_ids = $room_id_stmt->fetchAll();
            
            foreach($room_ids as $room) {
                $room_id[] = $room['id'];
            }
            
        
        
        
        
         $qry = "SELECT arc.accommodation_room_id as room_id,arc.date,arc.rate,arc.available FROM AccommodationRoomCalendar arc 
                                            WHERE accommodation_room_id in (".implode(',',$room_id).") 
                                                AND arc.date BETWEEN '".date('Y-m-d',$request->get('start'))."' AND '".date('Y-m-d',$request->get('end'))."'";
            $bookedStmt = $em->getConnection()->prepare($qry);
            $bookedStmt->execute();
            $room_calendars = $bookedStmt->fetchAll();
                     
//                     $room_calendars = $q->result();
       
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $return_events = array();
        
        foreach($room_calendars as $event) {
            $event['available'] = $event['available'] == 1?true:false;
            $return_events[] = $event;    
        }
//         d(json_encode($return_events));exit;
        $response->setContent(json_encode($return_events));
        
        return $response;
    }
}
