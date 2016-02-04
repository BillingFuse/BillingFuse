<?php
/*
*
* BillingFuse
*
* @copyright 2016 BillingFuse International Limited.
*
* @license Apache V2.0
*
* THIS SOURCE CODE FORM IS SUBJECT TO THE TERMS OF THE PUBLIC
* APACHE LICENSE V2.0. A COMPLETE COPY OF THE LICENSE TEXT IS
* INCLUDED IN THE LICENSE FILE. 
*
*/

class Fuse_EventDispatcher
{
  protected $listeners = array();

  /**
   * Connects a listener to a given event name.
   *
   * @param string  $name      An event name
   * @param mixed   $listener  A PHP callable
   */
  public function connect($name, $listener)
  {
    if (!isset($this->listeners[$name]))
    {
      $this->listeners[$name] = array();
    }

    $this->listeners[$name][] = $listener;
  }

  /**
   * Disconnects a listener for a given event name.
   *
   * @param string   $name      An event name
   * @param mixed    $listener  A PHP callable
   *
   * @return false|null false if listener does not exist, null otherwise
   */
  public function disconnect($name, $listener)
  {
    if (!isset($this->listeners[$name]))
    {
      return false;
    }

    foreach ($this->listeners[$name] as $i => $callable)
    {
      if ($listener === $callable)
      {
        unset($this->listeners[$name][$i]);
      }
    }
  }

  /**
   * Disconnects all listeners for a given event name.
   *
   * @param string   $name      An event name
   *
   * @return false|null false if listener does not exist, null otherwise
   */
  public function disconnectAll($name)
  {
    if (!isset($this->listeners[$name]))
    {
      return false;
    }

    foreach ($this->listeners[$name] as $i => $callable)
    {
        unset($this->listeners[$name][$i]);
    }
  }

  /**
   * Notifies all listeners of a given event.
   *
   * @param Fuse_Event $event A Fuse_Event instance
   *
   * @return Fuse_Event The Fuse_Event instance
   */
  public function notify(Fuse_Event $event)
  {
    foreach ($this->getListeners($event->getName()) as $listener)
    {
      call_user_func($listener, $event);
    }

    return $event;
  }

  /**
   * Notifies all listeners of a given event until one returns a non null value.
   *
   * @param  Fuse_Event $event A Fuse_Event instance
   *
   * @return Fuse_Event The Fuse_Event instance
   */
  public function notifyUntil(Fuse_Event $event)
  {
    foreach ($this->getListeners($event->getName()) as $listener)
    {
      if (call_user_func($listener, $event))
      {
        $event->setProcessed(true);
        break;
      }
    }

    return $event;
  }

  /**
   * Filters a value by calling all listeners of a given event.
   *
   * @param  Fuse_Event  $event   A Fuse_Event instance
   * @param  mixed    $value   The value to be filtered
   *
   * @return Fuse_Event The Fuse_Event instance
   */
  public function filter(Fuse_Event $event, $value)
  {
    foreach ($this->getListeners($event->getName()) as $listener)
    {
      $value = call_user_func_array($listener, array($event, $value));
    }

    $event->setReturnValue($value);

    return $event;
  }

  /**
   * Returns true if the given event name has some listeners.
   *
   * @param  string   $name    The event name
   *
   * @return Boolean true if some listeners are connected, false otherwise
   */
  public function hasListeners($name)
  {
    if (!isset($this->listeners[$name]))
    {
      $this->listeners[$name] = array();
    }

    return (boolean) count($this->listeners[$name]);
  }

  /**
   * Returns all listeners associated with a given event name.
   *
   * @param  string   $name    The event name
   *
   * @return array  An array of listeners
   */
  public function getListeners($name)
  {
    if (!isset($this->listeners[$name]))
    {
      return array();
    }

    return $this->listeners[$name];
  }
}