/**
 * Firebase Data Connect
 *
 * @packageDocumentation
 */

import { AppCheckInternalComponentName } from '@firebase/app-check-interop-types';
import { AppCheckTokenListener } from '@firebase/app-check-interop-types';
import { AppCheckTokenResult } from '@firebase/app-check-interop-types';
import { FirebaseApp } from '@firebase/app';
import { FirebaseAuthInternalName } from '@firebase/auth-interop-types';
import { FirebaseAuthTokenData } from '@firebase/auth-interop-types';
import { FirebaseError } from '@firebase/util';
import { LogLevelString } from '@firebase/logger';
import { Provider } from '@firebase/component';

/* Excluded from this release type: AppCheckTokenProvider */

declare type AuthTokenListener = (token: string | null) => void;

declare interface AuthTokenProvider {
    getToken(forceRefresh: boolean): Promise<FirebaseAuthTokenData | null>;
    addTokenChangeListener(listener: AuthTokenListener): void;
}

export declare interface CancellableOperation<T> extends PromiseLike<{
    data: T;
}> {
    cancel: () => void;
}

/**
 * Connect to the DataConnect Emulator
 * @param dc Data Connect instance
 * @param host host of emulator server
 * @param port port of emulator server
 * @param sslEnabled use https
 */
export declare function connectDataConnectEmulator(dc: DataConnect, host: string, port?: number, sslEnabled?: boolean): void;

/**
 * Connector Config for calling Data Connect backend.
 */
export declare interface ConnectorConfig {
    location: string;
    connector: string;
    service: string;
}

/**
 * Class representing Firebase Data Connect
 */
export declare class DataConnect {
    readonly app: FirebaseApp;
    private readonly dataConnectOptions;
    private readonly _authProvider;
    private readonly _appCheckProvider;
    _queryManager: QueryManager;
    _mutationManager: MutationManager;
    isEmulator: boolean;
    _initialized: boolean;
    private _transport;
    private _transportClass;
    private _transportOptions?;
    private _authTokenProvider?;
    _isUsingGeneratedSdk: boolean;
    private _appCheckTokenProvider?;
    constructor(app: FirebaseApp, dataConnectOptions: DataConnectOptions, _authProvider: Provider<FirebaseAuthInternalName>, _appCheckProvider: Provider<AppCheckInternalComponentName>);
    _useGeneratedSdk(): void;
    _delete(): Promise<void>;
    getSettings(): ConnectorConfig;
    setInitialized(): void;
    enableEmulator(transportOptions: TransportOptions): void;
}

/** An error returned by a DataConnect operation. */
declare class DataConnectError extends FirebaseError {
    /**
     * The backend error code associated with this error.
     */
    readonly code: DataConnectErrorCode;
    /**
     * A custom error description.
     */
    readonly message: string;
    /** The stack of the error. */
    readonly stack?: string;
    /** @hideconstructor */
    constructor(
    /**
     * The backend error code associated with this error.
     */
    code: DataConnectErrorCode, 
    /**
     * A custom error description.
     */
    message: string);
}

declare type DataConnectErrorCode = 'other' | 'already-initialized' | 'not-initialized' | 'not-supported' | 'invalid-argument' | 'partial-error' | 'unauthorized';

/**
 * DataConnectOptions including project id
 */
export declare interface DataConnectOptions extends ConnectorConfig {
    projectId: string;
}

export declare interface DataConnectResult<Data, Variables> extends OpResult<Data> {
    ref: OperationRef<Data, Variables>;
}

/**
 * Representation of user provided subscription options.
 */
export declare interface DataConnectSubscription<Data, Variables> {
    userCallback: OnResultSubscription<Data, Variables>;
    errCallback?: (e?: DataConnectError) => void;
    unsubscribe: () => void;
}

/* Excluded from this release type: DataConnectTransport */

export declare type DataSource = typeof SOURCE_CACHE | typeof SOURCE_SERVER;

/**
 * Execute Mutation
 * @param mutationRef mutation to execute
 * @returns `MutationRef`
 */
export declare function executeMutation<Data, Variables>(mutationRef: MutationRef<Data, Variables>): MutationPromise<Data, Variables>;

/**
 * Execute Query
 * @param queryRef query to execute.
 * @returns `QueryPromise`
 */
export declare function executeQuery<Data, Variables>(queryRef: QueryRef<Data, Variables>): QueryPromise<Data, Variables>;

/**
 * Initialize DataConnect instance
 * @param options ConnectorConfig
 */
export declare function getDataConnect(options: ConnectorConfig): DataConnect;

/**
 * Initialize DataConnect instance
 * @param app FirebaseApp to initialize to.
 * @param options ConnectorConfig
 */
export declare function getDataConnect(app: FirebaseApp, options: ConnectorConfig): DataConnect;

export declare const MUTATION_STR = "mutation";

/* Excluded from this release type: MutationManager */

/**
 * Mutation return value from `executeMutation`
 */
export declare interface MutationPromise<Data, Variables> extends PromiseLike<MutationResult<Data, Variables>> {
}

export declare interface MutationRef<Data, Variables> extends OperationRef<Data, Variables> {
    refType: typeof MUTATION_STR;
}

/**
 * Creates a `MutationRef`
 * @param dcInstance Data Connect instance
 * @param mutationName name of mutation
 */
export declare function mutationRef<Data>(dcInstance: DataConnect, mutationName: string): MutationRef<Data, undefined>;

/**
 *
 * @param dcInstance Data Connect instance
 * @param mutationName name of mutation
 * @param variables variables to send with mutation
 */
export declare function mutationRef<Data, Variables>(dcInstance: DataConnect, mutationName: string, variables: Variables): MutationRef<Data, Variables>;

/**
 * Mutation Result from `executeMutation`
 */
export declare interface MutationResult<Data, Variables> extends DataConnectResult<Data, Variables> {
    ref: MutationRef<Data, Variables>;
}

/**
 * `OnCompleteSubscription`
 */
export declare type OnCompleteSubscription = () => void;

/**
 * Signature for `OnErrorSubscription` for `subscribe`
 */
export declare type OnErrorSubscription = (err?: DataConnectError) => void;

/**
 * Signature for `OnResultSubscription` for `subscribe`
 */
export declare type OnResultSubscription<Data, Variables> = (res: QueryResult<Data, Variables>) => void;

export declare interface OperationRef<_Data, Variables> {
    name: string;
    variables: Variables;
    refType: ReferenceType;
    dataConnect: DataConnect;
}

export declare interface OpResult<Data> {
    data: Data;
    source: DataSource;
    fetchTime: string;
}

declare interface ParsedArgs<Variables> {
    dc: DataConnect;
    vars: Variables;
}

/* Excluded from this release type: parseOptions */

export declare const QUERY_STR = "query";

declare class QueryManager {
    private transport;
    _queries: Map<string, TrackedQuery<unknown, unknown>>;
    constructor(transport: DataConnectTransport);
    track<Data, Variables>(queryName: string, variables: Variables, initialCache?: OpResult<Data>): TrackedQuery<Data, Variables>;
    addSubscription<Data, Variables>(queryRef: OperationRef<Data, Variables>, onResultCallback: OnResultSubscription<Data, Variables>, onErrorCallback?: OnErrorSubscription, initialCache?: OpResult<Data>): () => void;
    executeQuery<Data, Variables>(queryRef: QueryRef<Data, Variables>): QueryPromise<Data, Variables>;
    enableEmulator(host: string, port: number): void;
}

/**
 * Promise returned from `executeQuery`
 */
export declare interface QueryPromise<Data, Variables> extends PromiseLike<QueryResult<Data, Variables>> {
}

/**
 * QueryRef object
 */
export declare interface QueryRef<Data, Variables> extends OperationRef<Data, Variables> {
    refType: typeof QUERY_STR;
}

/**
 * Execute Query
 * @param dcInstance Data Connect instance to use.
 * @param queryName Query to execute
 * @returns `QueryRef`
 */
export declare function queryRef<Data>(dcInstance: DataConnect, queryName: string): QueryRef<Data, undefined>;

/**
 * Execute Query
 * @param dcInstance Data Connect instance to use.
 * @param queryName Query to execute
 * @param variables Variables to execute with
 * @returns `QueryRef`
 */
export declare function queryRef<Data, Variables>(dcInstance: DataConnect, queryName: string, variables: Variables): QueryRef<Data, Variables>;

/**
 * Result of `executeQuery`
 */
export declare interface QueryResult<Data, Variables> extends DataConnectResult<Data, Variables> {
    ref: QueryRef<Data, Variables>;
    toJSON: () => SerializedRef<Data, Variables>;
}

/**
 * Signature for unsubscribe from `subscribe`
 */
export declare type QueryUnsubscribe = () => void;

export declare type ReferenceType = typeof QUERY_STR | typeof MUTATION_STR;

/**
 * Serialized RefInfo as a result of `QueryResult.toJSON().refInfo`
 */
export declare interface RefInfo<Variables> {
    name: string;
    variables: Variables;
    connectorConfig: DataConnectOptions;
}

/**
 * Serialized Ref as a result of `QueryResult.toJSON()`
 */
export declare interface SerializedRef<Data, Variables> extends OpResult<Data> {
    refInfo: RefInfo<Variables>;
}

export declare function setLogLevel(logLevel: LogLevelString): void;

export declare const SOURCE_CACHE = "CACHE";

export declare const SOURCE_SERVER = "SERVER";

/**
 * Subscribe to a `QueryRef`
 * @param queryRefOrSerializedResult query ref or serialized result.
 * @param observer observer object to use for subscribing.
 * @returns `SubscriptionOptions`
 */
export declare function subscribe<Data, Variables>(queryRefOrSerializedResult: QueryRef<Data, Variables> | SerializedRef<Data, Variables>, observer: SubscriptionOptions<Data, Variables>): QueryUnsubscribe;

/**
 * Subscribe to a `QueryRef`
 * @param queryRefOrSerializedResult query ref or serialized result.
 * @param onNext Callback to call when result comes back.
 * @param onError Callback to call when error gets thrown.
 * @param onComplete Called when subscription completes.
 * @returns `SubscriptionOptions`
 */
export declare function subscribe<Data, Variables>(queryRefOrSerializedResult: QueryRef<Data, Variables> | SerializedRef<Data, Variables>, onNext: OnResultSubscription<Data, Variables>, onError?: OnErrorSubscription, onComplete?: OnCompleteSubscription): QueryUnsubscribe;

/**
 * Representation of full observer options in `subscribe`
 */
export declare interface SubscriptionOptions<Data, Variables> {
    onNext?: OnResultSubscription<Data, Variables>;
    onErr?: OnErrorSubscription;
    onComplete?: OnCompleteSubscription;
}

/**
 * Delete DataConnect instance
 * @param dataConnect DataConnect instance
 * @returns
 */
export declare function terminate(dataConnect: DataConnect): Promise<void>;

/**
 * Converts serialized ref to query ref
 * @param serializedRef ref to convert to `QueryRef`
 * @returns `QueryRef`
 */
export declare function toQueryRef<Data, Variables>(serializedRef: SerializedRef<Data, Variables>): QueryRef<Data, Variables>;

declare interface TrackedQuery<Data, Variables> {
    ref: Omit<OperationRef<Data, Variables>, 'dataConnect'>;
    subscriptions: Array<DataConnectSubscription<Data, Variables>>;
    currentCache: OpResult<Data> | null;
    lastError: DataConnectError | null;
}

/* Excluded from this release type: TransportClass */

/**
 * Options to connect to emulator
 */
export declare interface TransportOptions {
    host: string;
    sslEnabled?: boolean;
    port?: number;
}

/* Excluded from this release type: validateArgs */

/* Excluded from this release type: validateDCOptions */

export { }