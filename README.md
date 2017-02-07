# Quest System

## Quest Components

#### Task
Quests can have a list of tasks which are required to be completed before the quest can be finished (e.g. User needs to login 5x).
A quest can have multiple tasks. The boolean operators ``AND`` and ``OR`` can be used to combine tasks. These operators can also be nested (e.g. User needs to login 5x OR The Sun is shining).

#### Task Completion Guard
The ``IsCompletedListener`` is a guard to make sure the quest can only change state to the ``completed`` state if the task are all finished.

#### Log
The quest log allows logging of every state change. It can serve two purposes:
* it makes the debug process easier because we know exactly when things happened
* it can be shown to the user as quest activity log

## Quest States

#### Available
The initial state the quest reaches once it leaves the quest pool.

#### In Progress
The quest was started and the user is working on completing all tasks.

#### Completed
The tasks in this quests are completed

#### Finished
The tasks are completed and the reward, if any, was collected.
The user is done with the quest and it should not be displayed anymore.

#### Rejected
The user or the system decided to abort or reject the quest.
The user is done with the quest and it should not be displayed anymore.
