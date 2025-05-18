import { reactive } from 'vue';

const eventBus = reactive(new Map());

export function emit(event, payload) {
    if (eventBus.has(event)) {
        eventBus.get(event)?.forEach((callback) => callback(payload));
    }
}

export function on(event, callback) {
    if (!eventBus.has(event)) {
        eventBus.set(event, []);
    }
    eventBus.get(event)?.push(callback);
}

export function off(event, callback) {
    if (eventBus.has(event)) {
        const callbacks = eventBus.get(event) || [];
        eventBus.set(
            event,
            callbacks.filter((cb) => cb !== callback),
        );
    }
}
