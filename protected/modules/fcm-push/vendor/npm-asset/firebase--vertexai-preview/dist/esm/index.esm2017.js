import { _getProvider, getApp, _registerComponent, registerVersion } from '@firebase/app';
import { Component } from '@firebase/component';
import { FirebaseError, getModularInstance } from '@firebase/util';
import { __asyncGenerator, __await } from 'tslib';

var name = "@firebase/vertexai-preview";
var version = "0.0.4";

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
const VERTEX_TYPE = 'vertexAI';
const DEFAULT_LOCATION = 'us-central1';
const DEFAULT_BASE_URL = 'https://firebaseml.googleapis.com';
const DEFAULT_API_VERSION = 'v2beta';
const PACKAGE_VERSION = version;
const LANGUAGE_TAG = 'gl-js';

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class VertexAIService {
    constructor(app, authProvider, appCheckProvider, options) {
        var _a;
        this.app = app;
        this.options = options;
        const appCheck = appCheckProvider === null || appCheckProvider === void 0 ? void 0 : appCheckProvider.getImmediate({ optional: true });
        const auth = authProvider === null || authProvider === void 0 ? void 0 : authProvider.getImmediate({ optional: true });
        this.auth = auth || null;
        this.appCheck = appCheck || null;
        this.location = ((_a = this.options) === null || _a === void 0 ? void 0 : _a.location) || DEFAULT_LOCATION;
    }
    _delete() {
        return Promise.resolve();
    }
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Error class for the Vertex AI in Firebase SDK.
 *
 * @public
 */
class VertexAIError extends FirebaseError {
    /**
     * Constructs a new instance of the `VertexAIError` class.
     *
     * @param code - The error code from {@link VertexAIErrorCode}.
     * @param message - A human-readable message describing the error.
     * @param customErrorData - Optional error data.
     */
    constructor(code, message, customErrorData) {
        // Match error format used by FirebaseError from ErrorFactory
        const service = VERTEX_TYPE;
        const serviceName = 'VertexAI';
        const fullCode = `${service}/${code}`;
        const fullMessage = `${serviceName}: ${message} (${fullCode}).`;
        super(fullCode, fullMessage);
        this.code = code;
        this.message = message;
        this.customErrorData = customErrorData;
        // FirebaseError initializes a stack trace, but it assumes the error is created from the error
        // factory. Since we break this assumption, we set the stack trace to be originating from this
        // constructor.
        // This is only supported in V8.
        if (Error.captureStackTrace) {
            // Allows us to initialize the stack trace without including the constructor itself at the
            // top level of the stack trace.
            Error.captureStackTrace(this, VertexAIError);
        }
        // Allows instanceof VertexAIError in ES5/ES6
        // https://github.com/Microsoft/TypeScript-wiki/blob/master/Breaking-Changes.md#extending-built-ins-like-error-array-and-map-may-no-longer-work
        Object.setPrototypeOf(this, VertexAIError.prototype);
        // Since Error is an interface, we don't inherit toString and so we define it ourselves.
        this.toString = () => fullMessage;
    }
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
var Task;
(function (Task) {
    Task["GENERATE_CONTENT"] = "generateContent";
    Task["STREAM_GENERATE_CONTENT"] = "streamGenerateContent";
    Task["COUNT_TOKENS"] = "countTokens";
})(Task || (Task = {}));
class RequestUrl {
    constructor(model, task, apiSettings, stream, requestOptions) {
        this.model = model;
        this.task = task;
        this.apiSettings = apiSettings;
        this.stream = stream;
        this.requestOptions = requestOptions;
    }
    toString() {
        var _a;
        // TODO: allow user-set option if that feature becomes available
        const apiVersion = DEFAULT_API_VERSION;
        const baseUrl = ((_a = this.requestOptions) === null || _a === void 0 ? void 0 : _a.baseUrl) || DEFAULT_BASE_URL;
        let url = `${baseUrl}/${apiVersion}`;
        url += `/projects/${this.apiSettings.project}`;
        url += `/locations/${this.apiSettings.location}`;
        url += `/${this.model}`;
        url += `:${this.task}`;
        if (this.stream) {
            url += '?alt=sse';
        }
        return url;
    }
    /**
     * If the model needs to be passed to the backend, it needs to
     * include project and location path.
     */
    get fullModelString() {
        let modelString = `projects/${this.apiSettings.project}`;
        modelString += `/locations/${this.apiSettings.location}`;
        modelString += `/${this.model}`;
        return modelString;
    }
}
/**
 * Log language and "fire/version" to x-goog-api-client
 */
function getClientHeaders() {
    const loggingTags = [];
    loggingTags.push(`${LANGUAGE_TAG}/${PACKAGE_VERSION}`);
    loggingTags.push(`fire/${PACKAGE_VERSION}`);
    return loggingTags.join(' ');
}
async function getHeaders(url) {
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-goog-api-client', getClientHeaders());
    headers.append('x-goog-api-key', url.apiSettings.apiKey);
    if (url.apiSettings.getAppCheckToken) {
        const appCheckToken = await url.apiSettings.getAppCheckToken();
        if (appCheckToken && !appCheckToken.error) {
            headers.append('X-Firebase-AppCheck', appCheckToken.token);
        }
    }
    if (url.apiSettings.getAuthToken) {
        const authToken = await url.apiSettings.getAuthToken();
        if (authToken) {
            headers.append('Authorization', `Firebase ${authToken.accessToken}`);
        }
    }
    return headers;
}
async function constructRequest(model, task, apiSettings, stream, body, requestOptions) {
    const url = new RequestUrl(model, task, apiSettings, stream, requestOptions);
    return {
        url: url.toString(),
        fetchOptions: Object.assign(Object.assign({}, buildFetchOptions(requestOptions)), { method: 'POST', headers: await getHeaders(url), body })
    };
}
async function makeRequest(model, task, apiSettings, stream, body, requestOptions) {
    const url = new RequestUrl(model, task, apiSettings, stream, requestOptions);
    let response;
    try {
        const request = await constructRequest(model, task, apiSettings, stream, body, requestOptions);
        response = await fetch(request.url, request.fetchOptions);
        if (!response.ok) {
            let message = '';
            let errorDetails;
            try {
                const json = await response.json();
                message = json.error.message;
                if (json.error.details) {
                    message += ` ${JSON.stringify(json.error.details)}`;
                    errorDetails = json.error.details;
                }
            }
            catch (e) {
                // ignored
            }
            throw new VertexAIError("fetch-error" /* VertexAIErrorCode.FETCH_ERROR */, `Error fetching from ${url}: [${response.status} ${response.statusText}] ${message}`, {
                status: response.status,
                statusText: response.statusText,
                errorDetails
            });
        }
    }
    catch (e) {
        let err = e;
        if (e.code !== "fetch-error" /* VertexAIErrorCode.FETCH_ERROR */ &&
            e instanceof Error) {
            err = new VertexAIError("error" /* VertexAIErrorCode.ERROR */, `Error fetching from ${url.toString()}: ${e.message}`);
            err.stack = e.stack;
        }
        throw err;
    }
    return response;
}
/**
 * Generates the request options to be passed to the fetch API.
 * @param requestOptions - The user-defined request options.
 * @returns The generated request options.
 */
function buildFetchOptions(requestOptions) {
    const fetchOptions = {};
    if ((requestOptions === null || requestOptions === void 0 ? void 0 : requestOptions.timeout) && (requestOptions === null || requestOptions === void 0 ? void 0 : requestOptions.timeout) >= 0) {
        const abortController = new AbortController();
        const signal = abortController.signal;
        setTimeout(() => abortController.abort(), requestOptions.timeout);
        fetchOptions.signal = signal;
    }
    return fetchOptions;
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Possible roles.
 * @public
 */
const POSSIBLE_ROLES = ['user', 'model', 'function', 'system'];
/**
 * Harm categories that would cause prompts or candidates to be blocked.
 * @public
 */
var HarmCategory;
(function (HarmCategory) {
    HarmCategory["HARM_CATEGORY_UNSPECIFIED"] = "HARM_CATEGORY_UNSPECIFIED";
    HarmCategory["HARM_CATEGORY_HATE_SPEECH"] = "HARM_CATEGORY_HATE_SPEECH";
    HarmCategory["HARM_CATEGORY_SEXUALLY_EXPLICIT"] = "HARM_CATEGORY_SEXUALLY_EXPLICIT";
    HarmCategory["HARM_CATEGORY_HARASSMENT"] = "HARM_CATEGORY_HARASSMENT";
    HarmCategory["HARM_CATEGORY_DANGEROUS_CONTENT"] = "HARM_CATEGORY_DANGEROUS_CONTENT";
})(HarmCategory || (HarmCategory = {}));
/**
 * Threshold above which a prompt or candidate will be blocked.
 * @public
 */
var HarmBlockThreshold;
(function (HarmBlockThreshold) {
    // Threshold is unspecified.
    HarmBlockThreshold["HARM_BLOCK_THRESHOLD_UNSPECIFIED"] = "HARM_BLOCK_THRESHOLD_UNSPECIFIED";
    // Content with NEGLIGIBLE will be allowed.
    HarmBlockThreshold["BLOCK_LOW_AND_ABOVE"] = "BLOCK_LOW_AND_ABOVE";
    // Content with NEGLIGIBLE and LOW will be allowed.
    HarmBlockThreshold["BLOCK_MEDIUM_AND_ABOVE"] = "BLOCK_MEDIUM_AND_ABOVE";
    // Content with NEGLIGIBLE, LOW, and MEDIUM will be allowed.
    HarmBlockThreshold["BLOCK_ONLY_HIGH"] = "BLOCK_ONLY_HIGH";
    // All content will be allowed.
    HarmBlockThreshold["BLOCK_NONE"] = "BLOCK_NONE";
})(HarmBlockThreshold || (HarmBlockThreshold = {}));
/**
 * @public
 */
var HarmBlockMethod;
(function (HarmBlockMethod) {
    // The harm block method is unspecified.
    HarmBlockMethod["HARM_BLOCK_METHOD_UNSPECIFIED"] = "HARM_BLOCK_METHOD_UNSPECIFIED";
    // The harm block method uses both probability and severity scores.
    HarmBlockMethod["SEVERITY"] = "SEVERITY";
    // The harm block method uses the probability score.
    HarmBlockMethod["PROBABILITY"] = "PROBABILITY";
})(HarmBlockMethod || (HarmBlockMethod = {}));
/**
 * Probability that a prompt or candidate matches a harm category.
 * @public
 */
var HarmProbability;
(function (HarmProbability) {
    // Probability is unspecified.
    HarmProbability["HARM_PROBABILITY_UNSPECIFIED"] = "HARM_PROBABILITY_UNSPECIFIED";
    // Content has a negligible chance of being unsafe.
    HarmProbability["NEGLIGIBLE"] = "NEGLIGIBLE";
    // Content has a low chance of being unsafe.
    HarmProbability["LOW"] = "LOW";
    // Content has a medium chance of being unsafe.
    HarmProbability["MEDIUM"] = "MEDIUM";
    // Content has a high chance of being unsafe.
    HarmProbability["HIGH"] = "HIGH";
})(HarmProbability || (HarmProbability = {}));
/**
 * Harm severity levels.
 * @public
 */
var HarmSeverity;
(function (HarmSeverity) {
    // Harm severity unspecified.
    HarmSeverity["HARM_SEVERITY_UNSPECIFIED"] = "HARM_SEVERITY_UNSPECIFIED";
    // Negligible level of harm severity.
    HarmSeverity["HARM_SEVERITY_NEGLIGIBLE"] = "HARM_SEVERITY_NEGLIGIBLE";
    // Low level of harm severity.
    HarmSeverity["HARM_SEVERITY_LOW"] = "HARM_SEVERITY_LOW";
    // Medium level of harm severity.
    HarmSeverity["HARM_SEVERITY_MEDIUM"] = "HARM_SEVERITY_MEDIUM";
    // High level of harm severity.
    HarmSeverity["HARM_SEVERITY_HIGH"] = "HARM_SEVERITY_HIGH";
})(HarmSeverity || (HarmSeverity = {}));
/**
 * Reason that a prompt was blocked.
 * @public
 */
var BlockReason;
(function (BlockReason) {
    // A blocked reason was not specified.
    BlockReason["BLOCKED_REASON_UNSPECIFIED"] = "BLOCKED_REASON_UNSPECIFIED";
    // Content was blocked by safety settings.
    BlockReason["SAFETY"] = "SAFETY";
    // Content was blocked, but the reason is uncategorized.
    BlockReason["OTHER"] = "OTHER";
})(BlockReason || (BlockReason = {}));
/**
 * Reason that a candidate finished.
 * @public
 */
var FinishReason;
(function (FinishReason) {
    // Default value. This value is unused.
    FinishReason["FINISH_REASON_UNSPECIFIED"] = "FINISH_REASON_UNSPECIFIED";
    // Natural stop point of the model or provided stop sequence.
    FinishReason["STOP"] = "STOP";
    // The maximum number of tokens as specified in the request was reached.
    FinishReason["MAX_TOKENS"] = "MAX_TOKENS";
    // The candidate content was flagged for safety reasons.
    FinishReason["SAFETY"] = "SAFETY";
    // The candidate content was flagged for recitation reasons.
    FinishReason["RECITATION"] = "RECITATION";
    // Unknown reason.
    FinishReason["OTHER"] = "OTHER";
})(FinishReason || (FinishReason = {}));
/**
 * @public
 */
var FunctionCallingMode;
(function (FunctionCallingMode) {
    // Unspecified function calling mode. This value should not be used.
    FunctionCallingMode["MODE_UNSPECIFIED"] = "MODE_UNSPECIFIED";
    // Default model behavior, model decides to predict either a function call
    // or a natural language response.
    FunctionCallingMode["AUTO"] = "AUTO";
    // Model is constrained to always predicting a function call only.
    // If "allowed_function_names" is set, the predicted function call will be
    // limited to any one of "allowed_function_names", else the predicted
    // function call will be any one of the provided "function_declarations".
    FunctionCallingMode["ANY"] = "ANY";
    // Model will not predict any function call. Model behavior is same as when
    // not passing any function declarations.
    FunctionCallingMode["NONE"] = "NONE";
})(FunctionCallingMode || (FunctionCallingMode = {}));

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Contains the list of OpenAPI data types
 * as defined by https://swagger.io/docs/specification/data-models/data-types/
 * @public
 */
var FunctionDeclarationSchemaType;
(function (FunctionDeclarationSchemaType) {
    /** String type. */
    FunctionDeclarationSchemaType["STRING"] = "STRING";
    /** Number type. */
    FunctionDeclarationSchemaType["NUMBER"] = "NUMBER";
    /** Integer type. */
    FunctionDeclarationSchemaType["INTEGER"] = "INTEGER";
    /** Boolean type. */
    FunctionDeclarationSchemaType["BOOLEAN"] = "BOOLEAN";
    /** Array type. */
    FunctionDeclarationSchemaType["ARRAY"] = "ARRAY";
    /** Object type. */
    FunctionDeclarationSchemaType["OBJECT"] = "OBJECT";
})(FunctionDeclarationSchemaType || (FunctionDeclarationSchemaType = {}));

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Adds convenience helper methods to a response object, including stream
 * chunks (as long as each chunk is a complete GenerateContentResponse JSON).
 */
function addHelpers(response) {
    response.text = () => {
        if (response.candidates && response.candidates.length > 0) {
            if (response.candidates.length > 1) {
                console.warn(`This response had ${response.candidates.length} ` +
                    `candidates. Returning text from the first candidate only. ` +
                    `Access response.candidates directly to use the other candidates.`);
            }
            if (hadBadFinishReason(response.candidates[0])) {
                throw new VertexAIError("response-error" /* VertexAIErrorCode.RESPONSE_ERROR */, `Response error: ${formatBlockErrorMessage(response)}. Response body stored in error.response`, {
                    response
                });
            }
            return getText(response);
        }
        else if (response.promptFeedback) {
            throw new VertexAIError("response-error" /* VertexAIErrorCode.RESPONSE_ERROR */, `Text not available. ${formatBlockErrorMessage(response)}`, {
                response
            });
        }
        return '';
    };
    response.functionCalls = () => {
        if (response.candidates && response.candidates.length > 0) {
            if (response.candidates.length > 1) {
                console.warn(`This response had ${response.candidates.length} ` +
                    `candidates. Returning function calls from the first candidate only. ` +
                    `Access response.candidates directly to use the other candidates.`);
            }
            if (hadBadFinishReason(response.candidates[0])) {
                throw new VertexAIError("response-error" /* VertexAIErrorCode.RESPONSE_ERROR */, `Response error: ${formatBlockErrorMessage(response)}. Response body stored in error.response`, {
                    response
                });
            }
            return getFunctionCalls(response);
        }
        else if (response.promptFeedback) {
            throw new VertexAIError("response-error" /* VertexAIErrorCode.RESPONSE_ERROR */, `Function call not available. ${formatBlockErrorMessage(response)}`, {
                response
            });
        }
        return undefined;
    };
    return response;
}
/**
 * Returns all text found in all parts of first candidate.
 */
function getText(response) {
    var _a, _b, _c, _d;
    const textStrings = [];
    if ((_b = (_a = response.candidates) === null || _a === void 0 ? void 0 : _a[0].content) === null || _b === void 0 ? void 0 : _b.parts) {
        for (const part of (_d = (_c = response.candidates) === null || _c === void 0 ? void 0 : _c[0].content) === null || _d === void 0 ? void 0 : _d.parts) {
            if (part.text) {
                textStrings.push(part.text);
            }
        }
    }
    if (textStrings.length > 0) {
        return textStrings.join('');
    }
    else {
        return '';
    }
}
/**
 * Returns {@link FunctionCall}s associated with first candidate.
 */
function getFunctionCalls(response) {
    var _a, _b, _c, _d;
    const functionCalls = [];
    if ((_b = (_a = response.candidates) === null || _a === void 0 ? void 0 : _a[0].content) === null || _b === void 0 ? void 0 : _b.parts) {
        for (const part of (_d = (_c = response.candidates) === null || _c === void 0 ? void 0 : _c[0].content) === null || _d === void 0 ? void 0 : _d.parts) {
            if (part.functionCall) {
                functionCalls.push(part.functionCall);
            }
        }
    }
    if (functionCalls.length > 0) {
        return functionCalls;
    }
    else {
        return undefined;
    }
}
const badFinishReasons = [FinishReason.RECITATION, FinishReason.SAFETY];
function hadBadFinishReason(candidate) {
    return (!!candidate.finishReason &&
        badFinishReasons.includes(candidate.finishReason));
}
function formatBlockErrorMessage(response) {
    var _a, _b, _c;
    let message = '';
    if ((!response.candidates || response.candidates.length === 0) &&
        response.promptFeedback) {
        message += 'Response was blocked';
        if ((_a = response.promptFeedback) === null || _a === void 0 ? void 0 : _a.blockReason) {
            message += ` due to ${response.promptFeedback.blockReason}`;
        }
        if ((_b = response.promptFeedback) === null || _b === void 0 ? void 0 : _b.blockReasonMessage) {
            message += `: ${response.promptFeedback.blockReasonMessage}`;
        }
    }
    else if ((_c = response.candidates) === null || _c === void 0 ? void 0 : _c[0]) {
        const firstCandidate = response.candidates[0];
        if (hadBadFinishReason(firstCandidate)) {
            message += `Candidate was blocked due to ${firstCandidate.finishReason}`;
            if (firstCandidate.finishMessage) {
                message += `: ${firstCandidate.finishMessage}`;
            }
        }
    }
    return message;
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
const responseLineRE = /^data\: (.*)(?:\n\n|\r\r|\r\n\r\n)/;
/**
 * Process a response.body stream from the backend and return an
 * iterator that provides one complete GenerateContentResponse at a time
 * and a promise that resolves with a single aggregated
 * GenerateContentResponse.
 *
 * @param response - Response from a fetch call
 */
function processStream(response) {
    const inputStream = response.body.pipeThrough(new TextDecoderStream('utf8', { fatal: true }));
    const responseStream = getResponseStream(inputStream);
    const [stream1, stream2] = responseStream.tee();
    return {
        stream: generateResponseSequence(stream1),
        response: getResponsePromise(stream2)
    };
}
async function getResponsePromise(stream) {
    const allResponses = [];
    const reader = stream.getReader();
    while (true) {
        const { done, value } = await reader.read();
        if (done) {
            return addHelpers(aggregateResponses(allResponses));
        }
        allResponses.push(value);
    }
}
function generateResponseSequence(stream) {
    return __asyncGenerator(this, arguments, function* generateResponseSequence_1() {
        const reader = stream.getReader();
        while (true) {
            const { value, done } = yield __await(reader.read());
            if (done) {
                break;
            }
            yield yield __await(addHelpers(value));
        }
    });
}
/**
 * Reads a raw stream from the fetch response and join incomplete
 * chunks, returning a new stream that provides a single complete
 * GenerateContentResponse in each iteration.
 */
function getResponseStream(inputStream) {
    const reader = inputStream.getReader();
    const stream = new ReadableStream({
        start(controller) {
            let currentText = '';
            return pump();
            function pump() {
                return reader.read().then(({ value, done }) => {
                    if (done) {
                        if (currentText.trim()) {
                            controller.error(new VertexAIError("parse-failed" /* VertexAIErrorCode.PARSE_FAILED */, 'Failed to parse stream'));
                            return;
                        }
                        controller.close();
                        return;
                    }
                    currentText += value;
                    let match = currentText.match(responseLineRE);
                    let parsedResponse;
                    while (match) {
                        try {
                            parsedResponse = JSON.parse(match[1]);
                        }
                        catch (e) {
                            controller.error(new VertexAIError("parse-failed" /* VertexAIErrorCode.PARSE_FAILED */, `Error parsing JSON response: "${match[1]}`));
                            return;
                        }
                        controller.enqueue(parsedResponse);
                        currentText = currentText.substring(match[0].length);
                        match = currentText.match(responseLineRE);
                    }
                    return pump();
                });
            }
        }
    });
    return stream;
}
/**
 * Aggregates an array of `GenerateContentResponse`s into a single
 * GenerateContentResponse.
 */
function aggregateResponses(responses) {
    const lastResponse = responses[responses.length - 1];
    const aggregatedResponse = {
        promptFeedback: lastResponse === null || lastResponse === void 0 ? void 0 : lastResponse.promptFeedback
    };
    for (const response of responses) {
        if (response.candidates) {
            for (const candidate of response.candidates) {
                const i = candidate.index;
                if (!aggregatedResponse.candidates) {
                    aggregatedResponse.candidates = [];
                }
                if (!aggregatedResponse.candidates[i]) {
                    aggregatedResponse.candidates[i] = {
                        index: candidate.index
                    };
                }
                // Keep overwriting, the last one will be final
                aggregatedResponse.candidates[i].citationMetadata =
                    candidate.citationMetadata;
                aggregatedResponse.candidates[i].finishReason = candidate.finishReason;
                aggregatedResponse.candidates[i].finishMessage =
                    candidate.finishMessage;
                aggregatedResponse.candidates[i].safetyRatings =
                    candidate.safetyRatings;
                /**
                 * Candidates should always have content and parts, but this handles
                 * possible malformed responses.
                 */
                if (candidate.content && candidate.content.parts) {
                    if (!aggregatedResponse.candidates[i].content) {
                        aggregatedResponse.candidates[i].content = {
                            role: candidate.content.role || 'user',
                            parts: []
                        };
                    }
                    const newPart = {};
                    for (const part of candidate.content.parts) {
                        if (part.text) {
                            newPart.text = part.text;
                        }
                        if (part.functionCall) {
                            newPart.functionCall = part.functionCall;
                        }
                        if (Object.keys(newPart).length === 0) {
                            newPart.text = '';
                        }
                        aggregatedResponse.candidates[i].content.parts.push(newPart);
                    }
                }
            }
        }
    }
    return aggregatedResponse;
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
async function generateContentStream(apiSettings, model, params, requestOptions) {
    const response = await makeRequest(model, Task.STREAM_GENERATE_CONTENT, apiSettings, 
    /* stream */ true, JSON.stringify(params), requestOptions);
    return processStream(response);
}
async function generateContent(apiSettings, model, params, requestOptions) {
    const response = await makeRequest(model, Task.GENERATE_CONTENT, apiSettings, 
    /* stream */ false, JSON.stringify(params), requestOptions);
    const responseJson = await response.json();
    const enhancedResponse = addHelpers(responseJson);
    return {
        response: enhancedResponse
    };
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function formatSystemInstruction(input) {
    // null or undefined
    if (input == null) {
        return undefined;
    }
    else if (typeof input === 'string') {
        return { role: 'system', parts: [{ text: input }] };
    }
    else if (input.text) {
        return { role: 'system', parts: [input] };
    }
    else if (input.parts) {
        if (!input.role) {
            return { role: 'system', parts: input.parts };
        }
        else {
            return input;
        }
    }
}
function formatNewContent(request) {
    let newParts = [];
    if (typeof request === 'string') {
        newParts = [{ text: request }];
    }
    else {
        for (const partOrString of request) {
            if (typeof partOrString === 'string') {
                newParts.push({ text: partOrString });
            }
            else {
                newParts.push(partOrString);
            }
        }
    }
    return assignRoleToPartsAndValidateSendMessageRequest(newParts);
}
/**
 * When multiple Part types (i.e. FunctionResponsePart and TextPart) are
 * passed in a single Part array, we may need to assign different roles to each
 * part. Currently only FunctionResponsePart requires a role other than 'user'.
 * @private
 * @param parts Array of parts to pass to the model
 * @returns Array of content items
 */
function assignRoleToPartsAndValidateSendMessageRequest(parts) {
    const userContent = { role: 'user', parts: [] };
    const functionContent = { role: 'function', parts: [] };
    let hasUserContent = false;
    let hasFunctionContent = false;
    for (const part of parts) {
        if ('functionResponse' in part) {
            functionContent.parts.push(part);
            hasFunctionContent = true;
        }
        else {
            userContent.parts.push(part);
            hasUserContent = true;
        }
    }
    if (hasUserContent && hasFunctionContent) {
        throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, 'Within a single message, FunctionResponse cannot be mixed with other type of Part in the request for sending chat message.');
    }
    if (!hasUserContent && !hasFunctionContent) {
        throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, 'No Content is provided for sending chat message.');
    }
    if (hasUserContent) {
        return userContent;
    }
    return functionContent;
}
function formatGenerateContentInput(params) {
    let formattedRequest;
    if (params.contents) {
        formattedRequest = params;
    }
    else {
        // Array or string
        const content = formatNewContent(params);
        formattedRequest = { contents: [content] };
    }
    if (params.systemInstruction) {
        formattedRequest.systemInstruction = formatSystemInstruction(params.systemInstruction);
    }
    return formattedRequest;
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// https://ai.google.dev/api/rest/v1beta/Content#part
const VALID_PART_FIELDS = [
    'text',
    'inlineData',
    'functionCall',
    'functionResponse'
];
const VALID_PARTS_PER_ROLE = {
    user: ['text', 'inlineData'],
    function: ['functionResponse'],
    model: ['text', 'functionCall'],
    // System instructions shouldn't be in history anyway.
    system: ['text']
};
const VALID_PREVIOUS_CONTENT_ROLES = {
    user: ['model'],
    function: ['model'],
    model: ['user', 'function'],
    // System instructions shouldn't be in history.
    system: []
};
function validateChatHistory(history) {
    let prevContent = null;
    for (const currContent of history) {
        const { role, parts } = currContent;
        if (!prevContent && role !== 'user') {
            throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `First Content should be with role 'user', got ${role}`);
        }
        if (!POSSIBLE_ROLES.includes(role)) {
            throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `Each item should include role field. Got ${role} but valid roles are: ${JSON.stringify(POSSIBLE_ROLES)}`);
        }
        if (!Array.isArray(parts)) {
            throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `Content should have 'parts' but property with an array of Parts`);
        }
        if (parts.length === 0) {
            throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `Each Content should have at least one part`);
        }
        const countFields = {
            text: 0,
            inlineData: 0,
            functionCall: 0,
            functionResponse: 0
        };
        for (const part of parts) {
            for (const key of VALID_PART_FIELDS) {
                if (key in part) {
                    countFields[key] += 1;
                }
            }
        }
        const validParts = VALID_PARTS_PER_ROLE[role];
        for (const key of VALID_PART_FIELDS) {
            if (!validParts.includes(key) && countFields[key] > 0) {
                throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `Content with role '${role}' can't contain '${key}' part`);
            }
        }
        if (prevContent) {
            const validPreviousContentRoles = VALID_PREVIOUS_CONTENT_ROLES[role];
            if (!validPreviousContentRoles.includes(prevContent.role)) {
                throw new VertexAIError("invalid-content" /* VertexAIErrorCode.INVALID_CONTENT */, `Content with role '${role} can't follow '${prevContent.role}'. Valid previous roles: ${JSON.stringify(VALID_PREVIOUS_CONTENT_ROLES)}`);
            }
        }
        prevContent = currContent;
    }
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Do not log a message for this error.
 */
const SILENT_ERROR = 'SILENT_ERROR';
/**
 * ChatSession class that enables sending chat messages and stores
 * history of sent and received messages so far.
 *
 * @public
 */
class ChatSession {
    constructor(apiSettings, model, params, requestOptions) {
        this.model = model;
        this.params = params;
        this.requestOptions = requestOptions;
        this._history = [];
        this._sendPromise = Promise.resolve();
        this._apiSettings = apiSettings;
        if (params === null || params === void 0 ? void 0 : params.history) {
            validateChatHistory(params.history);
            this._history = params.history;
        }
    }
    /**
     * Gets the chat history so far. Blocked prompts are not added to history.
     * Neither blocked candidates nor the prompts that generated them are added
     * to history.
     */
    async getHistory() {
        await this._sendPromise;
        return this._history;
    }
    /**
     * Sends a chat message and receives a non-streaming
     * {@link GenerateContentResult}
     */
    async sendMessage(request) {
        var _a, _b, _c, _d, _e;
        await this._sendPromise;
        const newContent = formatNewContent(request);
        const generateContentRequest = {
            safetySettings: (_a = this.params) === null || _a === void 0 ? void 0 : _a.safetySettings,
            generationConfig: (_b = this.params) === null || _b === void 0 ? void 0 : _b.generationConfig,
            tools: (_c = this.params) === null || _c === void 0 ? void 0 : _c.tools,
            toolConfig: (_d = this.params) === null || _d === void 0 ? void 0 : _d.toolConfig,
            systemInstruction: (_e = this.params) === null || _e === void 0 ? void 0 : _e.systemInstruction,
            contents: [...this._history, newContent]
        };
        let finalResult = {};
        // Add onto the chain.
        this._sendPromise = this._sendPromise
            .then(() => generateContent(this._apiSettings, this.model, generateContentRequest, this.requestOptions))
            .then(result => {
            var _a, _b;
            if (result.response.candidates &&
                result.response.candidates.length > 0) {
                this._history.push(newContent);
                const responseContent = {
                    parts: ((_a = result.response.candidates) === null || _a === void 0 ? void 0 : _a[0].content.parts) || [],
                    // Response seems to come back without a role set.
                    role: ((_b = result.response.candidates) === null || _b === void 0 ? void 0 : _b[0].content.role) || 'model'
                };
                this._history.push(responseContent);
            }
            else {
                const blockErrorMessage = formatBlockErrorMessage(result.response);
                if (blockErrorMessage) {
                    console.warn(`sendMessage() was unsuccessful. ${blockErrorMessage}. Inspect response object for details.`);
                }
            }
            finalResult = result;
        });
        await this._sendPromise;
        return finalResult;
    }
    /**
     * Sends a chat message and receives the response as a
     * {@link GenerateContentStreamResult} containing an iterable stream
     * and a response promise.
     */
    async sendMessageStream(request) {
        var _a, _b, _c, _d, _e;
        await this._sendPromise;
        const newContent = formatNewContent(request);
        const generateContentRequest = {
            safetySettings: (_a = this.params) === null || _a === void 0 ? void 0 : _a.safetySettings,
            generationConfig: (_b = this.params) === null || _b === void 0 ? void 0 : _b.generationConfig,
            tools: (_c = this.params) === null || _c === void 0 ? void 0 : _c.tools,
            toolConfig: (_d = this.params) === null || _d === void 0 ? void 0 : _d.toolConfig,
            systemInstruction: (_e = this.params) === null || _e === void 0 ? void 0 : _e.systemInstruction,
            contents: [...this._history, newContent]
        };
        const streamPromise = generateContentStream(this._apiSettings, this.model, generateContentRequest, this.requestOptions);
        // Add onto the chain.
        this._sendPromise = this._sendPromise
            .then(() => streamPromise)
            // This must be handled to avoid unhandled rejection, but jump
            // to the final catch block with a label to not log this error.
            .catch(_ignored => {
            throw new Error(SILENT_ERROR);
        })
            .then(streamResult => streamResult.response)
            .then(response => {
            if (response.candidates && response.candidates.length > 0) {
                this._history.push(newContent);
                const responseContent = Object.assign({}, response.candidates[0].content);
                // Response seems to come back without a role set.
                if (!responseContent.role) {
                    responseContent.role = 'model';
                }
                this._history.push(responseContent);
            }
            else {
                const blockErrorMessage = formatBlockErrorMessage(response);
                if (blockErrorMessage) {
                    console.warn(`sendMessageStream() was unsuccessful. ${blockErrorMessage}. Inspect response object for details.`);
                }
            }
        })
            .catch(e => {
            // Errors in streamPromise are already catchable by the user as
            // streamPromise is returned.
            // Avoid duplicating the error message in logs.
            if (e.message !== SILENT_ERROR) {
                // Users do not have access to _sendPromise to catch errors
                // downstream from streamPromise, so they should not throw.
                console.error(e);
            }
        });
        return streamPromise;
    }
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
async function countTokens(apiSettings, model, params, requestOptions) {
    const response = await makeRequest(model, Task.COUNT_TOKENS, apiSettings, false, JSON.stringify(params), requestOptions);
    return response.json();
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Class for generative model APIs.
 * @public
 */
class GenerativeModel {
    constructor(vertexAI, modelParams, requestOptions) {
        var _a, _b, _c, _d;
        if (!((_b = (_a = vertexAI.app) === null || _a === void 0 ? void 0 : _a.options) === null || _b === void 0 ? void 0 : _b.apiKey)) {
            throw new VertexAIError("no-api-key" /* VertexAIErrorCode.NO_API_KEY */, `The "apiKey" field is empty in the local Firebase config. Firebase VertexAI requires this field to contain a valid API key.`);
        }
        else if (!((_d = (_c = vertexAI.app) === null || _c === void 0 ? void 0 : _c.options) === null || _d === void 0 ? void 0 : _d.projectId)) {
            throw new VertexAIError("no-project-id" /* VertexAIErrorCode.NO_PROJECT_ID */, `The "projectId" field is empty in the local Firebase config. Firebase VertexAI requires this field to contain a valid project ID.`);
        }
        else {
            this._apiSettings = {
                apiKey: vertexAI.app.options.apiKey,
                project: vertexAI.app.options.projectId,
                location: vertexAI.location
            };
            if (vertexAI.appCheck) {
                this._apiSettings.getAppCheckToken = () => vertexAI.appCheck.getToken();
            }
            if (vertexAI.auth) {
                this._apiSettings.getAuthToken = () => vertexAI.auth.getToken();
            }
        }
        if (modelParams.model.includes('/')) {
            if (modelParams.model.startsWith('models/')) {
                // Add "publishers/google" if the user is only passing in 'models/model-name'.
                this.model = `publishers/google/${modelParams.model}`;
            }
            else {
                // Any other custom format (e.g. tuned models) must be passed in correctly.
                this.model = modelParams.model;
            }
        }
        else {
            // If path is not included, assume it's a non-tuned model.
            this.model = `publishers/google/models/${modelParams.model}`;
        }
        this.generationConfig = modelParams.generationConfig || {};
        this.safetySettings = modelParams.safetySettings || [];
        this.tools = modelParams.tools;
        this.toolConfig = modelParams.toolConfig;
        this.systemInstruction = formatSystemInstruction(modelParams.systemInstruction);
        this.requestOptions = requestOptions || {};
    }
    /**
     * Makes a single non-streaming call to the model
     * and returns an object containing a single {@link GenerateContentResponse}.
     */
    async generateContent(request) {
        const formattedParams = formatGenerateContentInput(request);
        return generateContent(this._apiSettings, this.model, Object.assign({ generationConfig: this.generationConfig, safetySettings: this.safetySettings, tools: this.tools, toolConfig: this.toolConfig, systemInstruction: this.systemInstruction }, formattedParams), this.requestOptions);
    }
    /**
     * Makes a single streaming call to the model
     * and returns an object containing an iterable stream that iterates
     * over all chunks in the streaming response as well as
     * a promise that returns the final aggregated response.
     */
    async generateContentStream(request) {
        const formattedParams = formatGenerateContentInput(request);
        return generateContentStream(this._apiSettings, this.model, Object.assign({ generationConfig: this.generationConfig, safetySettings: this.safetySettings, tools: this.tools, toolConfig: this.toolConfig, systemInstruction: this.systemInstruction }, formattedParams), this.requestOptions);
    }
    /**
     * Gets a new {@link ChatSession} instance which can be used for
     * multi-turn chats.
     */
    startChat(startChatParams) {
        return new ChatSession(this._apiSettings, this.model, Object.assign({ tools: this.tools, toolConfig: this.toolConfig, systemInstruction: this.systemInstruction }, startChatParams), this.requestOptions);
    }
    /**
     * Counts the tokens in the provided request.
     */
    async countTokens(request) {
        const formattedParams = formatGenerateContentInput(request);
        return countTokens(this._apiSettings, this.model, formattedParams);
    }
}

/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Returns a {@link VertexAI} instance for the given app.
 *
 * @public
 *
 * @param app - The {@link @firebase/app#FirebaseApp} to use.
 */
function getVertexAI(app = getApp(), options) {
    app = getModularInstance(app);
    // Dependencies
    const vertexProvider = _getProvider(app, VERTEX_TYPE);
    return vertexProvider.getImmediate({
        identifier: (options === null || options === void 0 ? void 0 : options.location) || DEFAULT_LOCATION
    });
}
/**
 * Returns a {@link GenerativeModel} class with methods for inference
 * and other functionality.
 *
 * @public
 */
function getGenerativeModel(vertexAI, modelParams, requestOptions) {
    if (!modelParams.model) {
        throw new VertexAIError("no-model" /* VertexAIErrorCode.NO_MODEL */, `Must provide a model name. Example: getGenerativeModel({ model: 'my-model-name' })`);
    }
    return new GenerativeModel(vertexAI, modelParams, requestOptions);
}

/**
 * The Vertex AI in Firebase Web SDK.
 *
 * @packageDocumentation
 */
function registerVertex() {
    _registerComponent(new Component(VERTEX_TYPE, (container, { instanceIdentifier: location }) => {
        // getImmediate for FirebaseApp will always succeed
        const app = container.getProvider('app').getImmediate();
        const auth = container.getProvider('auth-internal');
        const appCheckProvider = container.getProvider('app-check-internal');
        return new VertexAIService(app, auth, appCheckProvider, { location });
    }, "PUBLIC" /* ComponentType.PUBLIC */).setMultipleInstances(true));
    registerVersion(name, version);
    // BUILD_TARGET will be replaced by values like esm5, esm2017, cjs5, etc during the compilation
    registerVersion(name, version, 'esm2017');
}
registerVertex();

export { BlockReason, ChatSession, FinishReason, FunctionCallingMode, FunctionDeclarationSchemaType, GenerativeModel, HarmBlockMethod, HarmBlockThreshold, HarmCategory, HarmProbability, HarmSeverity, POSSIBLE_ROLES, VertexAIError, getGenerativeModel, getVertexAI };
//# sourceMappingURL=index.esm2017.js.map
