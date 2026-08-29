/**
 * JavaScript файл для функционала тестов
 * Управляет интерактивностью тестов: мгновенная обратная связь, блокировка вопросов
 * Использует data-атрибуты для выбора элементов
 *
 * @author IT-Tools Project
 * @version 1.0
 */

document.addEventListener("DOMContentLoaded", function () {
  // Инициализация тестов
  initTests();
});

/**
 * Инициализация функционала тестов
 */
function initTests() {
  const testAnswers = document.querySelectorAll("[data-test-answer]");

  if (testAnswers.length === 0) return;

  // Добавление обработчиков событий для всех ответов
  testAnswers.forEach((answer) => {
    answer.addEventListener("click", handleTestAnswer);
  });
}

/**
 * Обработка клика по ответу на тест
 * @param {Event} event - Событие клика
 */
function handleTestAnswer(event) {
  const clickedAnswer = event.currentTarget;
  const questionNumber = clickedAnswer.getAttribute("data-test-question");
  const answerNumber = clickedAnswer.getAttribute("data-test-answer");
  const correctAnswer = parseInt(clickedAnswer.getAttribute("data-correct"));

  // Проверка, уже ли отвечен вопрос
  const questionElement = document.querySelector(
    `[data-test-question="${questionNumber}"]`,
  );
  if (questionElement.classList.contains("test-question--answered")) {
    return; // Вопрос уже отвечен, блокируем повторные клики
  }

  // Блокировка всего вопроса
  questionElement.classList.add("test-question--answered");

  // Блокировка всех ответов в этом вопросе
  const allAnswersInQuestion =
    questionElement.querySelectorAll("[data-test-answer]");
  allAnswersInQuestion.forEach((answer) => {
    answer.disabled = true;
    answer.classList.add("test-answer--disabled");
  });

  // Проверка правильности ответа
  const isCorrect = parseInt(answerNumber) === correctAnswer;

  if (isCorrect) {
    // Правильный ответ
    clickedAnswer.classList.add("test-answer--correct");
    showFeedback(clickedAnswer, "correct");
  } else {
    // Неправильный ответ
    clickedAnswer.classList.add("test-answer--incorrect");
    showFeedback(clickedAnswer, "incorrect");

    // Подсветка правильного ответа
    const correctAnswerElement = questionElement.querySelector(
      `[data-test-answer="${correctAnswer}"]`,
    );
    if (correctAnswerElement) {
      correctAnswerElement.classList.add("test-answer--correct");
      showFeedback(correctAnswerElement, "correct");
    }
  }
}

/**
 * Отображение обратной связи (галочка или крестик)
 * @param {HTMLElement} element - Элемент ответа
 * @param {string} type - Тип обратной связи ('correct' или 'incorrect')
 */
function showFeedback(element, type) {
  const marker = element.querySelector(".test-answer__marker");
  if (!marker) return;

  // Полностью очищаем маркер
  marker.textContent = "";
  marker.innerHTML = "";

  // Удаляем все дочерние узлы
  while (marker.firstChild) {
    marker.removeChild(marker.firstChild);
  }

  if (type === "correct") {
    marker.classList.add("test-answer__marker--correct");
  } else {
    marker.textContent = "✖";
    marker.classList.add("test-answer__marker--incorrect");
  }
}

/**
 * Проверка статуса вопроса (отвечен или нет)
 * @param {number} questionNumber - Номер вопроса
 * @return {boolean} true если вопрос уже отвечен
 */
function isQuestionAnswered(questionNumber) {
  const questionElement = document.querySelector(
    `[data-test-question="${questionNumber}"]`,
  );
  return (
    questionElement &&
    questionElement.classList.contains("test-question--answered")
  );
}

/**
 * Получение статистики по тестам
 * @return {Object} Объект со статистикой (total, answered, correct)
 */
function getTestStatistics() {
  const questions = document.querySelectorAll("[data-test-question]");
  const totalQuestions = questions.length;
  let answeredQuestions = 0;
  let correctAnswers = 0;

  questions.forEach((question) => {
    if (question.classList.contains("test-question--answered")) {
      answeredQuestions++;

      const correctAnswer = question.querySelector(".test-answer--correct");
      if (
        correctAnswer &&
        correctAnswer.classList.contains("test-answer--correct")
      ) {
        correctAnswers++;
      }
    }
  });

  return {
    total: totalQuestions,
    answered: answeredQuestions,
    correct: correctAnswers,
    percentage:
      totalQuestions > 0
        ? Math.round((correctAnswers / totalQuestions) * 100)
        : 0,
  };
}

/**
 * Сброс всех тестов (для тестирования)
 * В реальном приложении этот функционал может не понадобиться
 */
function resetAllTests() {
  const questions = document.querySelectorAll("[data-test-question]");

  questions.forEach((question) => {
    question.classList.remove("test-question--answered");

    const answers = question.querySelectorAll("[data-test-answer]");
    answers.forEach((answer) => {
      answer.disabled = false;
      answer.classList.remove(
        "test-answer--disabled",
        "test-answer--correct",
        "test-answer--incorrect",
      );

      const marker = answer.querySelector(".test-answer__marker");
      if (marker) {
        marker.innerHTML = "";
        marker.classList.remove(
          "test-answer__marker--correct",
          "test-answer__marker--incorrect",
        );
      }
    });
  });
}
