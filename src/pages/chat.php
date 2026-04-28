<?php

$idR = $_GET['idR'] ?? null;

?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TalkJS components tutorial</title>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@talkjs/web-components@0.1.10/default.css"
    />
    <script type="importmap">
      {
        "imports": {
          "@talkjs/web-components": "https://cdn.jsdelivr.net/npm/@talkjs/web-components@0.1.10",
          "@talkjs/core": "https://cdn.jsdelivr.net/npm/@talkjs/core@1.9.1"
        }
      }
    </script>
    <script type="module" async>
      import '@talkjs/web-components';
      import { getTalkSession } from '@talkjs/core';

      const appId = 'tG2pzpiO';

      const userId = 'frank';
      const otherUserId = 'nina';
      const conversationId = 'my_conversation';

      const session = getTalkSession({ appId, userId });

      session.currentUser.createIfNotExists({ name: 'Frank' });
      session.user(otherUserId).createIfNotExists({ name: 'Nina' });

      const conversation = session.conversation(conversationId);
      conversation.createIfNotExists();
      conversation.participant(otherUserId).createIfNotExists();
    </script>
  </head>
  <body>
    <t-chatbox
      style="width: 400px; height: 600px;"
      app-id="tG2pzpiO"
      user-id="frank"
      conversation-id="my_conversation"
    ></t-chatbox>
        <t-chatbox
      style="width: 400px; height: 600px;"
      app-id="tG2pzpiO"
      user-id="nina"
      conversation-id="my_conversation"
    ></t-chatbox>
  </body>
</html>