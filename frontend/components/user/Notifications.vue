<template>
  <div>
    <div
      w:mb="10"
    >
      <p
        w:text="2xl"
      >
        {{ $t('campaign.latestPosts') }}
      </p>
    </div>
    <div
      w:mb="10"
    >
      <p
        w:text="lg"
      >
        Heute
      </p>
      <div
        w:flex="~ row"
        w:mt="2"
      >
        <div
        w:w="10"
        w:h="10"
        w:rounded="full"
        w:mb="4"
        w:mr="3"
        w:overflow="hidden"
        w:flex="shrink-0"
        class="highlight-bg"
        >
          <img src="https://fakeimg.pl/40/" alt="Dummy Image" w:max-w="10" w:h="auto" />
        </div>
        <div
          w:flex="~ col grow"
        >
          <p w:mb="1">Lorem Ipsum sit dolor</p>
          <p w:text="sm" class="highlight-text">Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
        </div>
      </div>
    </div>
    <div
      w:mb="10"
    >
      <p
        w:text="lg"
      >
        22. Dez.
      </p>
      <div
        w:flex="~ row"
        w:mt="2"
      >
        <div
        w:w="10"
        w:h="10"
        w:rounded="full"
        w:mb="4"
        w:mr="3"
        w:overflow="hidden"
        w:flex="shrink-0"
        class="highlight-bg"
        >
          <img src="https://fakeimg.pl/40/" alt="Dummy Image" w:max-w="10" w:h="auto" />
        </div>
        <div
          w:flex="~ col grow"
        >
          <p w:mb="1">Lorem Ipsum sit dolor</p>
          <p w:text="sm" class="highlight-text">Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
        </div>
      </div>
    </div>
  </div>
</template>